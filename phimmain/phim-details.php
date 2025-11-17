<?php
/**
 * Trang Chi Tiết Phim - Hệ Thống Đặt Vé Xem Phim
 */

// BẬT HIỂN THỊ LỖI ĐỂ DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/function.php';

// Giải phóng ghế hết hạn
giaiPhongGheHetHan();

// Lấy MaPhim từ GET
$maPhim = isset($_GET['ma_phim']) ? intval($_GET['ma_phim']) : 0;
if ($maPhim <= 0) {
    header("Location: index.php");
    exit;
}

// Lấy thông tin phim
$phim = getPhimDetail($maPhim);
if (!$phim) {
    header("Location: index.php");
    exit;
}

// Lấy danh sách suất chiếu hợp lệ
$suatChieu = getSuatChieuHopLe($maPhim);

// Xử lý các bước đặt vé
$step = isset($_POST['step']) ? $_POST['step'] : 'view';
$message = '';

// Kiểm tra đăng nhập và độ tuổi
if ($step !== 'view') {
    if (!isLoggedIn()) {
        $message = '<div class="alert alert-warning">Vui lòng đăng nhập để đặt vé.</div>';
        $step = 'view';
    } else {
        $maKH = $_SESSION['MaKH'];
        if (!kiemTraDoTuoiKhachHang($maKH, $maPhim)) {
            $message = '<div class="alert alert-danger">Bạn chưa đủ tuổi để xem phim này (giới hạn: ' . $phim['TuoiGioiHan'] . '+).</div>';
            $step = 'view';
        }
    }
}

$selectedSuat = null;
$soDoGhe = [];
if ($step === 'select_suat' && isset($_POST['ma_suat'])) {
    $selectedSuat = intval($_POST['ma_suat']);
    $soDoGhe = getSoDoGhe($selectedSuat);
    $step = 'select_ghe';
}

// Lấy thông tin suất chiếu và phòng nếu đã chọn suất
$suatInfo = null;
if ($selectedSuat) {
    $conn = connectDatabase();
    if ($conn) {
        $tblSuat = findExistingTable($conn, ['suatchieu', 'SuatChieu']);
        $tblPhong = findExistingTable($conn, ['phongchieu', 'PhongChieu']);
        if ($tblSuat && $tblPhong) {
            $sql = "SELECT s.NgayChieu, s.GioBatDau, p.TenPhong 
                    FROM `{$tblSuat}` s 
                    JOIN `{$tblPhong}` p ON s.MaPhong = p.MaPhong 
                    WHERE s.MaSuat = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('i', $selectedSuat);
                $stmt->execute();
                $result = $stmt->get_result();
                $suatInfo = $result->fetch_assoc();
                $stmt->close();
            } else {
                error_log("Prepare lỗi trong lấy suatInfo: " . $conn->error);
            }
        }
        $conn->close();
    }
}

if ($step === 'confirm' && isset($_POST['xac_nhan']) && isset($_SESSION['ma_phieu_tam'])) {
    $maPhieu = $_SESSION['ma_phieu_tam'];
    
    // DEBUG: Kiểm tra phiếu trước khi xác nhận
    error_log("DEBUG: Xác nhận phiếu - MaPhieu: " . $maPhieu);
    
    $conn = connectDatabase();
    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
    $tblSuat = findExistingTable($conn, ['suatchieu', 'SuatChieu']);
    $tblPhim = findExistingTable($conn, ['phim', 'Phim']);
    $tblPhong = findExistingTable($conn, ['phongchieu', 'PhongChieu']);
    $tblCT = findExistingTable($conn, ['chitietphieu', 'ChiTietPhieu']);
    $tblGhe = findExistingTable($conn, ['ghe', 'Ghe']);

    if ($tblPhieu && $tblSuat && $tblPhim && $tblPhong && $tblCT && $tblGhe) {
        $sqlCheck = "SELECT * FROM `{$tblPhieu}` WHERE MaPhieu = ? AND TrangThai = 'Tạm giữ'";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("i", $maPhieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $phieuInfo = $result->fetch_assoc();
        $stmt->close();
        
        if ($result->num_rows === 0) {
            error_log("ERROR: Phiếu không tồn tại hoặc không ở trạng thái 'Tạm giữ'");
            $message = '<div class="alert alert-danger">Phiếu không tồn tại hoặc đã được xử lý.</div>';
            $step = 'view';
        } else {
            error_log("DEBUG: Thông tin phiếu - MaSuat: " . $phieuInfo['MaSuat'] . ", MaKH: " . $phieuInfo['MaKH']);
            
            if (xacNhanPhieu($maPhieu)) {
                unset($_SESSION['ma_phieu_tam']);
                $message = '<div class="alert alert-success">Đặt vé thành công!</div>';
                $step = 'done'; // hoặc gán bước tiếp theo tùy logic bạn dùng
            } else {
                $message = '<div class="alert alert-danger">Lỗi xác nhận phiếu. Vui lòng thử lại.</div>';
                $step = 'confirm';
            }
        }
    } else {
        error_log("Một hoặc nhiều bảng không tồn tại.");
        $message = '<div class="alert alert-danger">Lỗi hệ thống.</div>';
        $step = 'view';
    }
    $conn->close();
}

// Gán biến hiển thị ra giao diện
$tenPhim = $phim['TenPhim'];
$ngayChieu = $suatInfo ? date("d/m/Y", strtotime($suatInfo['NgayChieu'])) : '';
$gioBatDau = $suatInfo ? date("H:i", strtotime($suatInfo['GioBatDau'])) : '';
$tenPhong = $suatInfo ? $suatInfo['TenPhong'] : '';

$selectedGhe = [];
$chiTietPhieu = [];
if ($step === 'select_ghe' && isset($_POST['ghe']) && is_array($_POST['ghe'])) {
    $selectedSuat = intval($_POST['ma_suat']);
    $dsGhe = [];
    
    foreach ($_POST['ghe'] as $maGhe) {
        $maGhe = intval($maGhe);
        $conn = connectDatabase();
        $tblGhe = findExistingTable($conn, ['ghe', 'Ghe']);
        if ($tblGhe) {
            $sql = "SELECT LoaiGhe, SoGhe FROM `{$tblGhe}` WHERE MaGhe = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $maGhe);
            $stmt->execute();
            $result = $stmt->get_result();
            $gheInfo = $result->fetch_assoc();
            $stmt->close();
            
            if ($gheInfo) {
                $dsGhe[] = [
                    'MaGhe' => $maGhe,
                    'LoaiGhe' => $gheInfo['LoaiGhe'],
                    'SoGhe' => $gheInfo['SoGhe']
                ];
            }
        }
        $conn->close();
    }

    if (!empty($dsGhe) && tamGiuGhe($selectedSuat, array_column($dsGhe, 'MaGhe'))) {
        $maPhieu = taoPhieuTamTinh($_SESSION['MaKH'], $selectedSuat, $dsGhe, $phim['GiaCoBan']);
        if ($maPhieu) {
            $_SESSION['ma_phieu_tam'] = $maPhieu;
            $message = '<div class="alert alert-success">Phiếu tạm tính đã tạo. Vui lòng xác nhận trong 10 phút.</div>';
            $step = 'confirm';
        } else {
            $message = '<div class="alert alert-danger">Lỗi tạo phiếu tạm tính.</div>';
            $step = 'select_ghe';
        }
    } else {
        $message = '<div class="alert alert-danger">Lỗi tạm giữ ghế. Một số ghế có thể đã được đặt.</div>';
        $step = 'select_ghe';
    }
}

if ($step === 'confirm' && isset($_POST['xac_nhan']) && isset($_SESSION['ma_phieu_tam'])) {
    $maPhieu = $_SESSION['ma_phieu_tam'];
    
    // DEBUG: Kiểm tra phiếu trước khi xác nhận
    error_log("DEBUG: Xác nhận phiếu - MaPhieu: " . $maPhieu);
    
    $conn = connectDatabase();
    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
    if ($tblPhieu) {
        $sqlCheck = "SELECT * FROM `{$tblPhieu}` WHERE MaPhieu = ? AND TrangThai = 'Tạm giữ'";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("i", $maPhieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $phieuInfo = $result->fetch_assoc();
        $stmt->close();
        
        if ($result->num_rows === 0) {
            error_log("ERROR: Phiếu không tồn tại hoặc không ở trạng thái 'Tạm giữ'");
            $message = '<div class="alert alert-danger">Phiếu không tồn tại hoặc đã được xử lý.</div>';
            $step = 'view';
        } else {
            error_log("DEBUG: Thông tin phiếu - MaSuat: " . $phieuInfo['MaSuat'] . ", MaKH: " . $phieuInfo['MaKH']);
            
            if (xacNhanPhieu($maPhieu)) {
                $message = '<div class="alert alert-success">Đặt vé thành công! Phiếu đã xác nhận. <a href="index.php" class="text-white underline">Quay lại trang chủ</a></div>';
                unset($_SESSION['ma_phieu_tam']);
                $step = 'view';
                header("Location: index.php");  // Thêm redirect sau thành công
                exit;
            } else {
                $message = '<div class="alert alert-danger">Lỗi xác nhận phiếu. Vui lòng thử lại.</div>';
                $step = 'confirm';
            }
        }
    } else {
        error_log("Bảng phieutamtinh không tồn tại.");
        $message = '<div class="alert alert-danger">Lỗi hệ thống.</div>';
        $step = 'view';
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi Tiết Phim: <?php echo htmlspecialchars($phim['TenPhim']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar { background: #0B0D13; }
        .wrapper { height: 250px; }
        @media (min-width: 768px) { .wrapper { height: 300px; } }
        @media (min-width: 1280px) { .wrapper { height: 473px; } }
        
        .ghe-btn {
            transition: all 0.3s ease;
            transform: scale(1);
        }
        
        .ghe-btn:hover:not(:disabled) {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .ghe-btn:active:not(:disabled) {
            transform: scale(0.95);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .ghe-btn.bg-blue-500 {
            animation: pulse 2s infinite;
        }
        
        @media (max-width: 640px) {
            .ghe-btn {
                width: 32px !important;
                height: 32px !important;
                font-size: 10px !important;
            }
        }
        
.alert { 
    position: fixed;          /* Đè lên header */
    top: 15px;                /* Cách mép trên một chút */
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;            /* Luôn nằm trên header và các phần khác */
    
    margin: 0;
    max-width: 600px; 
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    animation: fadeDown 0.4s ease;
}

/* Màu sắc các loại thông báo */
.alert-success { 
    background: #d4edda; 
    color: #155724; 
    border: 1px solid #c3e6cb; 
}

.alert-danger { 
    background: #f8d7da; 
    color: #721c24; 
    border: 1px solid #f5c6cb; 
}

.alert-warning { 
    background: #fff3cd; 
    color: #856404; 
    border: 1px solid #ffeaa7; 
}

/* Hiệu ứng xuất hiện */
@keyframes fadeDown {
    from { opacity: 0; transform: translate(-50%, -20px); }
    to { opacity: 1; transform: translate(-50%, 0); }
}
</style>

</head>
<body class="bg-gray-900 text-white">
    <!-- Thông báo -->
    <?php if (!empty($message)): echo $message; endif; ?>

    <!-- Navbar -->
    <nav class="fixed w-full bg-gray-900 z-50 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold text-white">CINEMA</a>
            <div class="space-x-4">
                <?php if (isLoggedIn()): ?>
                    <span class="text-white">Xin chào, <?php echo htmlspecialchars($_SESSION['HoTen'] ?? ''); ?></span>
                    <a href="logout.php" class="text-white hover:text-red-500">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-red-500">Đăng nhập</a>
                    <a href="register.php" class="text-white hover:text-red-500">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

<!-- Thông tin phim -->
    <div class="pt-20">
        <div class="relative wrapper">
            <!-- Desktop Layout -->
            <div class="hidden xl:block absolute w-full inset-0 m-auto z-20 transform -translate-y-10">
                <div class="w-full max-w-3xl m-auto h-full py-4 pt-16 flex gap-6">
                    <div class="relative h-[260px] min-w-[180px] shadow-lg">
                        <img src="<?php echo htmlspecialchars($phim['Poster']); ?>" 
                             alt="<?php echo htmlspecialchars($phim['TenPhim']); ?>" 
                             class="object-cover rounded-xl w-full h-full">
                    </div>
                    <div class="text-sm flex flex-col text-white">
                        <div class="flex items-center gap-2 mt-2">
                            <h3 class="font-bold text-2xl"><?php echo htmlspecialchars($phim['TenPhim']); ?></h3>
                            <div class="rounded-xl p-2 border border-white font-bold">2D</div>
                        </div>
                        
                        <div class="flex items-center mt-2 gap-5 text-sm">
                            <p><i class="fas fa-clock mr-1"></i><?php echo $phim['ThoiLuong']; ?> phút</p>
                            <p><i class="fas fa-calendar mr-1"></i>Khởi chiếu: <?php echo date('d/m/Y', strtotime($phim['NgayKhoiChieu'])); ?></p>
                        </div>
                        
                        <p class="mt-5 line-clamp-4"><?php echo htmlspecialchars($phim['MoTaNgan']); ?></p>
                        
                        <div class="text-red-500 mt-5">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Giới hạn tuổi: <?php echo $phim['TuoiGioiHan']; ?>+
                        </div>
                        
                        <div class="mt-5">
                            <p><i class="fas fa-ticket-alt mr-2"></i>Giá vé thường: <strong><?php echo number_format($phim['GiaCoBan'], 0, ',', '.'); ?> VNĐ</strong></p>
                            <p><i class="fas fa-crown mr-2"></i>Giá vé VIP: <strong><?php echo number_format($phim['GiaCoBan'] * 1.2, 0, ',', '.'); ?> VNĐ</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Layout -->
            <div class="block xl:hidden px-4 md:px-6 pb-10 -mt-[120px] z-30 relative">
                <div class="flex items-center gap-4">
                    <div class="h-[200px] w-full max-w-[150px] md:w-[200px] md:max-w-[200px] md:h-[250px] relative">
                        <img src="<?php echo htmlspecialchars($phim['Poster']); ?>" 
                             alt="<?php echo htmlspecialchars($phim['TenPhim']); ?>" 
                             class="object-cover rounded-xl shadow-lg w-full h-full">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mt-2">
                            <h3 class="font-bold md:text-xl text-white"><?php echo htmlspecialchars($phim['TenPhim']); ?></h3>
                            <div class="rounded-md p-1 border border-white font-bold text-sm md:text-base">2D</div>
                        </div>
                        <div class="flex items-center mt-2 gap-x-2 text-sm md:text-base flex-wrap text-white">
                            <p><i class="fas fa-clock mr-1"></i><?php echo $phim['ThoiLuong']; ?> phút</p>
                        </div>
                    </div>
                </div>
                <div class="text-sm flex flex-col mt-4 text-white">
                    <p><i class="fas fa-calendar mr-2"></i>Khởi chiếu: <?php echo date('d/m/Y', strtotime($phim['NgayKhoiChieu'])); ?></p>
                    <p class="mt-4 line-clamp-4"><?php echo htmlspecialchars($phim['MoTaNgan']); ?></p>
                    <div class="text-red-500 mt-5">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Giới hạn tuổi: <?php echo $phim['TuoiGioiHan']; ?>+
                    </div>
                    <div class="mt-4">
                        <p><i class="fas fa-ticket-alt mr-2"></i>Giá vé: <strong><?php echo number_format($phim['GiaCoBan'], 0, ',', '.'); ?> VNĐ</strong></p>
                        <p><i class="fas fa-crown mr-2"></i>VIP: <strong><?php echo number_format($phim['GiaCoBan'] * 1.2, 0, ',', '.'); ?> VNĐ</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <!-- Suất chiếu -->
<?php if ($step === 'view' || $step === 'select_suat'): ?>
    <div class="w-full bg-gray-800 py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-6">Chọn Suất Chiếu</h2>
            
            <?php if (empty($suatChieu)): ?>
                <div class="text-center text-gray-400">
                    <i class="fas fa-film text-4xl mb-4"></i>
                    <p>Hiện không có suất chiếu nào cho phim này.</p>
                </div>
            <?php else: ?>
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach ($suatChieu as $suat): ?>
                            <form method="POST" class="text-center">
                                <input type="hidden" name="step" value="select_suat">
                                <input type="hidden" name="ma_suat" value="<?php echo $suat['MaSuat']; ?>">
                                <button type="submit" 
                                        class="w-full bg-gray-700 hover:bg-red-600 text-white py-3 px-4 rounded-lg transition duration-300">
                                    <div class="font-bold text-lg">
                                        <?php echo date('H:i', strtotime($suat['GioBatDau'])); ?>
                                    </div>
                                    <div class="text-sm text-gray-300">
                                        Ngày chiếu: <?php echo date('d/m/Y', strtotime($suat['NgayChieu'])); ?>
                                    </div>
                                    <div class="text-sm text-gray-300">
                                        <?php echo htmlspecialchars($suat['TenPhong']); ?>
                                    </div>
                                    <div class="text-sm text-green-400">
                                        <?php echo $suat['GheTrong']; ?> ghế trống
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

    <!-- Sơ đồ ghế -->
    <?php if ($step === 'select_ghe' && !empty($soDoGhe)): ?>
        <div class="w-full bg-gray-800 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">CHỌN GHẾ</h2>
                        <div class="flex justify-center items-center space-x-6 text-sm text-gray-600 flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span>Ghế trống</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                <span>Ghế tạm giữ</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                <span>Ghế đã đặt</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                <span>Ghế đang chọn</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 border-2 border-yellow-400 rounded mr-2"></div>
                                <span>Ghế VIP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Màn hình -->
                    <div class="text-center mb-8">
                        <div class="bg-gray-200 py-3 mx-auto max-w-md rounded-lg">
                            <span class="text-gray-700 font-semibold">MÀN HÌNH</span>
                        </div>
                    </div>

                    <!-- Sơ đồ ghế -->
                    <form method="POST" id="gheForm">
                        <input type="hidden" name="step" value="select_ghe">
                        <input type="hidden" name="ma_suat" value="<?php echo $selectedSuat; ?>">
                        
                        <div class="flex justify-center mb-8">
                            <div class="space-y-2">
                               <?php
                                $rows = [];
                                foreach ($soDoGhe as $ghe) {
                                    $row = preg_replace('/[0-9]/', '', $ghe['SoGhe']);
                                    $number = preg_replace('/[A-Z]/', '', $ghe['SoGhe']);
                                    $key = $row . $number; // Tạo key duy nhất dựa trên hàng và số ghế
                                    if (!isset($rows[$row][$key])) {
                                        $rows[$row][$key] = [
                                            'MaGhe' => $ghe['MaGhe'],
                                            'SoGhe' => $ghe['SoGhe'],
                                            'LoaiGhe' => $ghe['LoaiGhe'],
                                            'TrangThai' => $ghe['TrangThai'],
                                            'Number' => $number
                                        ];
                                    }
                                }

                                ksort($rows);
                                foreach ($rows as $rowLetter => $gheInRow) {
                                    $gheInRow = array_values($gheInRow); // Đảm bảo mảng được sắp xếp lại
                                    usort($gheInRow, function($a, $b) {
                                        return intval($a['Number']) - intval($b['Number']);
                                    });
                                    // Tiếp tục hiển thị như mã hiện tại
                                    echo '<div class="flex items-center justify-center space-x-2">';
                                    echo '<div class="w-8 text-center font-bold text-gray-700">' . $rowLetter . '</div>';
                                    foreach ($gheInRow as $ghe) {
                                        // Giữ nguyên logic hiển thị ghế
                                        $cssClass = '';
                                        $isDisabled = false;
                                        switch($ghe['TrangThai']) {
                                            case 'Trống':
                                            case 'trống':
                                                $cssClass = 'bg-green-500 hover:bg-green-600 text-white';
                                                break;
                                            case 'Tạm giữ':
                                                $cssClass = 'bg-yellow-500 text-gray-800 cursor-not-allowed';
                                                $isDisabled = true;
                                                break;
                                            case 'Đã đặt':
                                                $cssClass = 'bg-red-500 text-white cursor-not-allowed';
                                                $isDisabled = true;
                                                break;
                                            default:
                                                $cssClass = 'bg-gray-300 text-gray-600 cursor-not-allowed';
                                                $isDisabled = true;
                                        }
                                        if ($ghe['LoaiGhe'] === 'VIP') {
                                            $cssClass .= ' border-2 border-yellow-400';
                                        }
                                        echo '<button type="button" 
                                                    class="ghe-btn w-10 h-10 rounded-lg flex items-center justify-center text-sm font-semibold transition-all duration-200 ' . $cssClass . '"
                                                    data-ma-ghe="' . $ghe['MaGhe'] . '"
                                                    data-loai-ghe="' . $ghe['LoaiGhe'] . '"
                                                    data-so-ghe="' . $ghe['SoGhe'] . '"
                                                    data-trang-thai="' . $ghe['TrangThai'] . '"';
                                        if (!$isDisabled) {
                                            echo ' onclick="toggleGhe(this)"';
                                        } else {
                                            echo ' disabled';
                                        }
                                        echo '>' . $ghe['Number'] . '</button>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Thông tin đặt ghế -->
                        <div class="bg-gray-100 rounded-lg p-4 mb-6">
                            <h3 class="font-bold text-gray-800 mb-3">THÔNG TIN ĐẶT GHẾ</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Phim: <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($phim['TenPhim']); ?></span></p>
                                    <p class="text-sm text-gray-600">Suất chiếu: <span class="font-semibold text-gray-800">
                                        <?php 
                                        if ($suatInfo) {
                                            echo $ngayChieu . ' ' . $gioBatDau . ' - ' . htmlspecialchars($tenPhong);
                                        } else {
                                            echo '<span class="text-red-500">Không tìm thấy thông tin suất chiếu</span>';
                                        }
                                        ?>
                                    </span></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Ghế đã chọn: <span id="selectedSeatsList" class="font-semibold text-gray-800">Chưa chọn ghế</span></p>
                                    <p class="text-sm text-gray-600">Tổng tiền: <span id="totalPrice" class="font-semibold text-red-600 text-lg">0 VNĐ</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Nút điều khiển -->
                        <div class="flex justify-center space-x-4">
                            <a href="phim-details.php?ma_phim=<?php echo $maPhim; ?>" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                                Quay lại
                            </a>
                            <button type="submit" 
                                    id="submitBtn"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                Tiếp tục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            let selectedGhe = [];
            const giaThuong = <?php echo $phim['GiaCoBan']; ?>;
            const giaVIP = <?php echo $phim['GiaCoBan'] * 1.2; ?>;
            const maxGhe = 10;  // Thêm limit ghế chọn

            function toggleGhe(element) {
                const maGhe = element.getAttribute('data-ma-ghe');
                const loaiGhe = element.getAttribute('data-loai-ghe');
                const soGhe = element.getAttribute('data-so-ghe');
                
                const gheIndex = selectedGhe.findIndex(g => g.maGhe === maGhe);
                
                if (gheIndex === -1) {
                    if (selectedGhe.length >= maxGhe) {
                        alert('Bạn chỉ có thể chọn tối đa 10 ghế.');
                        return;
                    }
                    selectedGhe.push({ maGhe, loaiGhe, soGhe });
                    element.classList.remove('bg-green-500', 'hover:bg-green-600');
                    element.classList.add('bg-blue-500', 'hover:bg-blue-600');
                } else {
                    selectedGhe.splice(gheIndex, 1);
                    element.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    element.classList.add('bg-green-500', 'hover:bg-green-600');
                }
                
                updateSelectedSeats();
                updateHiddenInputs();
                updateSubmitButton();
            }

            function updateSelectedSeats() {
                const seatsList = document.getElementById('selectedSeatsList');
                const totalPrice = document.getElementById('totalPrice');
                
                if (selectedGhe.length === 0) {
                    seatsList.textContent = 'Chưa chọn ghế';
                    totalPrice.textContent = '0 VNĐ';
                    return;
                }
                
                const seatNames = selectedGhe.map(ghe => ghe.soGhe).join(', ');
                seatsList.textContent = seatNames;
                
                let tongTien = 0;
                selectedGhe.forEach(ghe => {
                    tongTien += (ghe.loaiGhe === 'VIP') ? giaVIP : giaThuong;
                });
                
                totalPrice.textContent = tongTien.toLocaleString('vi-VN') + ' VNĐ';
            }

            function updateHiddenInputs() {
                const form = document.getElementById('gheForm');
                const existingInputs = form.querySelectorAll('input[name="ghe[]"]');
                existingInputs.forEach(input => input.remove());
                
                selectedGhe.forEach(ghe => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ghe[]';
                    input.value = ghe.maGhe;
                    form.appendChild(input);
                });
            }

            function updateSubmitButton() {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = selectedGhe.length === 0;
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateSelectedSeats();
                updateSubmitButton();
            });
        </script>
    <?php endif; ?>

    <!-- Xác nhận phiếu -->
    <?php if ($step === 'confirm' && isset($_SESSION['ma_phieu_tam'])): ?>
        <div class="w-full bg-gray-800 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">XÁC NHẬN ĐẶT VÉ</h2>
                        <p class="text-green-600 font-semibold">Phiếu tạm tính đã được tạo. Vui lòng xác nhận trong vòng 10 phút.</p>
                    </div>

                    <?php
                    $conn = connectDatabase();
                    $maPhieu = $_SESSION['ma_phieu_tam'];
                    
                    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
                    $tblSuat = findExistingTable($conn, ['suatchieu', 'SuatChieu']);
                    $tblPhim = findExistingTable($conn, ['phim', 'Phim']);
                    $tblPhong = findExistingTable($conn, ['phongchieu', 'PhongChieu']);
                    $tblCT = findExistingTable($conn, ['chitietphieu', 'ChiTietPhieu']);
                    $tblGhe = findExistingTable($conn, ['ghe', 'Ghe']);

                    if ($tblPhieu && $tblSuat && $tblPhim && $tblPhong && $tblCT && $tblGhe) {
                        $sql = "
                            SELECT 
                                ptt.MaPhieu,
                                ptt.TongTien,
                                ptt.MaSuat,
                                p.TenPhim,
                                sc.NgayChieu,
                                sc.GioBatDau,
                                pc.TenPhong,
                                ct.MaGhe,
                                ct.DonGia,
                                ct.LoaiGhe,
                                g.SoGhe
                            FROM `{$tblPhieu}` ptt
                            INNER JOIN `{$tblSuat}` sc ON ptt.MaSuat = sc.MaSuat
                            INNER JOIN `{$tblPhim}` p ON sc.MaPhim = p.MaPhim
                            INNER JOIN `{$tblPhong}` pc ON sc.MaPhong = pc.MaPhong
                            INNER JOIN `{$tblCT}` ct ON ptt.MaPhieu = ct.MaPhieu
                            INNER JOIN `{$tblGhe}` g ON ct.MaGhe = g.MaGhe
                            WHERE ptt.MaPhieu = ?
                        ";
                        
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param("i", $maPhieu);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $ticketDetails = $result->fetch_all(MYSQLI_ASSOC);
                            $stmt->close();
                            
                            if (!empty($ticketDetails)) {
                                $firstTicket = $ticketDetails[0];
                    ?>
                    
 <!-- Thông tin vé -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">THÔNG TIN VÉ</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phim:
                                <span class="font-semibold"><?php echo htmlspecialchars($firstTicket['TenPhim']); ?></span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Suất chiếu:
                                    <span class="font-semibold">
                                    <?php echo date('d/m/Y', strtotime($firstTicket['NgayChieu'])) . ' ' . date('H:i', strtotime($firstTicket['GioBatDau'])); ?>
                                </span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phòng chiếu:
                                <span class="font-semibold"><?php echo htmlspecialchars($firstTicket['TenPhong']); ?></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết ghế -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">CHI TIẾT GHẾ</h3>
                        
                        <div class="space-y-2">
                            <?php foreach ($ticketDetails as $ticket): ?>
                                <div class="flex justify-between items-center py-2 border-b">
                                    <div>
                                        <span class="text-gray-600">Ghế <?php echo htmlspecialchars($ticket['SoGhe']); ?></span>
                                        <span class="text-sm text-gray-600 ml-2">(<?php echo htmlspecialchars($ticket['LoaiGhe']); ?>)</span>
                                    </div>
                                    <span class="font-semibold text-red-600">
                                        <?php echo number_format($ticket['DonGia'], 0, ',', '.'); ?> VNĐ
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="flex justify-between items-center pt-2 border-t-2 border-gray-300">
                                <span class="font-bold text-red-600 text-lg">Tổng cộng:</span>
                                <span class="font-bold text-red-600 text-lg">
<?php echo number_format($firstTicket['TongTien'], 0, ',', '.'); ?> VNĐ
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Nút xác nhận -->
                    <form method="POST" class="text-center">
                        <input type="hidden" name="step" value="confirm">
                        <input type="hidden" name="xac_nhan" value="1">
                        
                        <div class="flex justify-center space-x-4">
                            <a href="phim-details.php?ma_phim=<?php echo $maPhim; ?>" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                                Hủy bỏ
                            </a>
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                                Xác nhận đặt vé
                            </button>
                        </div>
                    </form>
                    
                    <?php
                        } else {
                            echo '<div class="alert alert-danger">Không tìm thấy thông tin phiếu. Vui lòng thử lại.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger">Lỗi truy vấn database.</div>';
                    }
                    } else {
                        echo '<div class="alert alert-danger">Một hoặc nhiều bảng không tồn tại.</div>';
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-[#0B0D13] mt-10">
        <div class="mx-auto p-8">
            <ul class="flex items-center justify-center flex-wrap gap-4 sm:gap-10 mb-6 text-sm md:text-base">
                <li><a href="/policy" class="hover:text-red-500">Chính sách</a></li>
                <li><a href="/movies" class="hover:text-red-500">Lịch chiếu</a></li>
                <li><a href="/news-list" class="hover:text-red-500">Tin tức</a></li>
                <li><a href="/ticket-price" class="hover:text-red-500">Giá vé</a></li>
                <li><a href="/faqs" class="hover:text-red-500">Hỏi đáp</a></li>
                <li><a href="/group-booking" class="hover:text-red-500">Đặt vé nhóm</a></li>
                <li><a href="/contact" class="hover:text-red-500">Liên hệ</a></li>
            </ul>
            <div class="mb-6 flex flex-wrap items-center justify-center gap-4 sm:gap-10">
                <div class="flex items-center gap-6">
                    <a href="https://www.facebook.com/example" target="_blank"><i class="fab fa-facebook text-2xl"></i></a>
                    <a href="https://zalo.me/example" target="_blank"><i class="fab fa-whatsapp text-2xl"></i></a>
                    <a href="https://youtube.com/example" target="_blank"><i class="fab fa-youtube text-2xl"></i></a>
                </div>
            </div>
            <div class="text-center space-y-2 text-xs md:text-base mb-6">
                <p>Cơ quan chủ quản: TMU</p>
                <p>Bản quyền thuộc sinh viên Trường Đại học Thương mại.</p>
                <p>Địa chỉ: Số 87 Láng Hạ, Phường Ô Chợ Dừa, TP.Hà Nội - Điện thoại: 024.35141791</p>
            </div>
            <div class="text-center text-sm">Copyright 2025. NHÓM 3.</div>
        </div>
    </footer>
</body>
</html>