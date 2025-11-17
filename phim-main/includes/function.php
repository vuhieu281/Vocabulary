<?php
/**
 * Các hàm tiện ích cho hệ thống đặt vé xem phim
 * Phiên bản đã được hardening: kiểm tra tồn tại bảng, thông báo lỗi rõ ràng
 */

require_once __DIR__ . '/config.php';

/**
 * Kiểm tra xem trong database hiện tại có bảng nào trong danh sách candidates không.
 * Trả về tên bảng thật nếu tìm thấy, ngược lại trả về false.
 */
function findExistingTable($conn, array $candidates) {
    $db = $conn->real_escape_string($conn->query("SELECT DATABASE()")->fetch_row()[0] ?? '');
    if (!$db) return false;

    $placeholders = implode("','", array_map([$conn, 'real_escape_string'], $candidates));
    $sql = "SELECT table_name FROM information_schema.tables
            WHERE table_schema = '{$db}' AND table_name IN ('{$placeholders}')
            LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row['table_name'];
    }
    return false;
}

/**
 * Lấy danh sách phim (đang chiếu hoặc sắp chiếu)
 * @param string $trangThai 'Đang chiếu' | 'Sắp chiếu'
 * @return array
 */
function getPhimList($trangThai = 'Đang chiếu') {
    $conn = connectDatabase();
    if (!$conn) return [];

    $tbl = findExistingTable($conn, ['phim', 'Phim']);
    if (!$tbl) {
        error_log("Bảng phim không tồn tại trong database.");
        $conn->close();
        return [];
    }

    $today = date('Y-m-d');

    $sql = "SELECT MaPhim, TenPhim, Poster, MoTaNgan, ThoiLuong, NgayKhoiChieu, TuoiGioiHan, TrangThai, GiaCoBan
            FROM `{$tbl}`";

    if ($trangThai === 'Đang chiếu') {
        $sql .= " WHERE NgayKhoiChieu <= ? ORDER BY NgayKhoiChieu DESC";
    } else {
        $sql .= " WHERE NgayKhoiChieu > ? ORDER BY NgayKhoiChieu ASC";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare lỗi trong getPhimList: " . $conn->error);
        $conn->close();
        return [];
    }
    $stmt->bind_param('s', $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $phimList = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $phimList;
}

/**
 * Kiểm tra độ tuổi khách hàng có đủ xem phim không
 */
function kiemTraDoTuoiPhim($tuoiKH, $tuoiGioiHan) {
    return intval($tuoiKH) >= intval($tuoiGioiHan);
}

/**
 * Lấy danh sách suất chiếu hợp lệ cho một phim
 * - Thời gian bắt đầu ≥ hiện tại + 1 giờ
 * - Còn ghế trống
 */
function getSuatChieuHopLe($maPhim) {
    $conn = connectDatabase();
    if (!$conn) return [];

    $tblSuat = findExistingTable($conn, ['suatchieu', 'SuatChieu']);
    $tblPhong = findExistingTable($conn, ['phongchieu', 'PhongChieu']);
    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);

    if (!$tblSuat || !$tblPhong || !$tblGheSuat) {
        error_log("Một hoặc nhiều bảng (suatchieu/phongchieu/ghesuatchieu) không tồn tại.");
        $conn->close();
        return [];
    }

    $sql = "
        SELECT s.MaSuat, s.NgayChieu, s.GioBatDau, p.TenPhong, p.TongSoGhe AS OfficialTongGhe,
               COUNT(DISTINCT gs.MaGhe) AS CalculatedTongGhe,
               SUM(CASE WHEN gs.TrangThai = 'Trống' THEN 1 ELSE 0 END) AS GheTrong
        FROM `{$tblSuat}` s
        JOIN `{$tblPhong}` p ON s.MaPhong = p.MaPhong
        LEFT JOIN `{$tblGheSuat}` gs ON s.MaSuat = gs.MaSuat
        WHERE s.MaPhim = ?
          AND TIMESTAMP(s.NgayChieu, s.GioBatDau) >= NOW() + INTERVAL 1 HOUR
        GROUP BY s.MaSuat, s.MaPhong, p.TongSoGhe
        HAVING CalculatedTongGhe <= OfficialTongGhe AND GheTrong > 0
        ORDER BY s.NgayChieu, s.GioBatDau
    ";

    $conn->query("SET time_zone = '+07:00';");

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare lỗi trong getSuatChieuHopLe: " . $conn->error);
        $conn->close();
        return [];
    }
    $stmt->bind_param('i', $maPhim);
    $stmt->execute();
    $result = $stmt->get_result();
    $suatList = $result->fetch_all(MYSQLI_ASSOC);

    error_log("getSuatChieuHopLe(MaPhim=$maPhim): " . json_encode($suatList));

    $stmt->close();
    $conn->close();
    return $suatList;
}

/**
 * Lấy sơ đồ ghế của suất chiếu
 */
function getSoDoGhe($maSuat) {
    $conn = connectDatabase();
    if (!$conn) return [];

    $tblGhe = findExistingTable($conn, ['ghe', 'Ghe']);
    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);

    if (!$tblGhe || !$tblGheSuat) {
        error_log("Bảng ghe hoặc ghesuatchieu không tồn tại.");
        $conn->close();
        return [];
    }

    $sql = "
        SELECT g.MaGhe, g.SoGhe, g.LoaiGhe, gs.TrangThai
        FROM `{$tblGhe}` g
        JOIN `{$tblGheSuat}` gs ON g.MaGhe = gs.MaGhe
        WHERE gs.MaSuat = ?
        ORDER BY SUBSTRING(g.SoGhe,1,1), CAST(SUBSTRING(g.SoGhe,2) AS UNSIGNED) ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare lỗi trong getSoDoGhe: " . $conn->error);
        $conn->close();
        return [];
    }
    $stmt->bind_param('i', $maSuat);
    $stmt->execute();
    $result = $stmt->get_result();
    $gheList = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $gheList;
}

/**
 * Tạm giữ ghế trong 10 phút
 * $dsGhe là mảng các MaGhe (int)
 */
function tamGiuGhe($maSuat, $dsGhe) {
    $conn = connectDatabase();
    if (!$conn) return false;

    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);
    if (!$tblGheSuat) {
        error_log("Bảng ghesuatchieu không tồn tại.");
        $conn->close();
        return false;
    }

    $conn->begin_transaction();
    try {
        $stmt = null;
        foreach ($dsGhe as $maGhe) {
            $stmt = $conn->prepare("
                UPDATE `{$tblGheSuat}`
                SET TrangThai = 'Tạm giữ'
                WHERE MaSuat = ? AND MaGhe = ? AND TrangThai = 'Trống'
            ");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param('ii', $maSuat, $maGhe);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Ghế (MaGhe={$maGhe}) không còn trống hoặc đã bị thay đổi.");
            }
            $stmt->close();
        }

        $conn->commit();
        $conn->close();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("tamGiuGhe lỗi: " . $e->getMessage());
        if ($stmt && is_object($stmt)) $stmt->close();
        $conn->close();
        return false;
    }
}

/**
 * Tạo phiếu tạm tính
 * $dsGhe: mảng mỗi phần tử là ['MaGhe'=>int, 'LoaiGhe'=>'Thường'|'VIP']
 */
function taoPhieuTamTinh($maKH, $maSuat, $dsGhe, $donGiaCB, $heSoVIP = 1.2) {
    $conn = connectDatabase();
    if (!$conn) return false;

    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
    $tblCT = findExistingTable($conn, ['chitietphieu', 'ChiTietPhieu']);
    $tblGhe = findExistingTable($conn, ['ghe', 'Ghe']);

    if (!$tblPhieu || !$tblCT || !$tblGhe) {
        error_log("Một hoặc nhiều bảng (phieutamtinh/chitietphieu/ghe) không tồn tại.");
        $conn->close();
        return false;
    }

    $tongTien = 0.0;
    foreach ($dsGhe as $g) {
        $don = (isset($g['LoaiGhe']) && strtolower($g['LoaiGhe']) === 'vip') ? ($donGiaCB * $heSoVIP) : $donGiaCB;
        $tongTien += $don;
    }

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("
            INSERT INTO `{$tblPhieu}` (MaKH, MaSuat, NgayLap, TongTien, TrangThai)
            VALUES (?, ?, NOW(), ?, 'Tạm giữ')
        ");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param('iid', $maKH, $maSuat, $tongTien);
        $stmt->execute();
        $maPhieu = $conn->insert_id;
        $stmt->close();

        $stmtCT = $conn->prepare("
            INSERT INTO `{$tblCT}` (MaPhieu, MaGhe, DonGia, LoaiGhe)
            VALUES (?, ?, ?, ?)
        ");
        if (!$stmtCT) throw new Exception($conn->error);

        foreach ($dsGhe as $g) {
            $don = (isset($g['LoaiGhe']) && strtolower($g['LoaiGhe']) === 'vip') ? ($donGiaCB * $heSoVIP) : $donGiaCB;
            $maGhe = $g['MaGhe'];
            $loai = $g['LoaiGhe'];
            $stmtCT->bind_param('iids', $maPhieu, $maGhe, $don, $loai);
            $stmtCT->execute();
        }
        $stmtCT->close();

        $conn->commit();
        $conn->close();
        return $maPhieu;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("taoPhieuTamTinh lỗi: " . $e->getMessage());
        if (isset($stmt) && is_object($stmt)) $stmt->close();
        if (isset($stmtCT) && is_object($stmtCT)) $stmtCT->close();
        $conn->close();
        return false;
    }
}

/**
 * Xác nhận phiếu → cập nhật ghế thành "Đã đặt"
 */
/**
 * Xác nhận phiếu → cập nhật ghế thành "Đã đặt"
 */
function xacNhanPhieu($maPhieu) {
    $conn = connectDatabase();
    if (!$conn) return false;

    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
    $tblCT = findExistingTable($conn, ['chitietphieu', 'ChiTietPhieu']);
    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);

    if (!$tblPhieu || !$tblCT || !$tblGheSuat) {
        error_log("Một hoặc nhiều bảng cần thiết không tồn tại.");
        $conn->close();
        return false;
    }

    $conn->begin_transaction();
    try {
        // 1. Cập nhật trạng thái phiếu thành 'Xác nhận'
        $stmt = $conn->prepare("UPDATE `{$tblPhieu}` SET TrangThai = 'Xác nhận' WHERE MaPhieu = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param('i', $maPhieu);
        $stmt->execute();
        $stmt->close();

        // 2. Lấy danh sách ghế & suất trong phiếu
        $sql = "
            SELECT ctp.MaGhe, ptt.MaSuat
            FROM `{$tblCT}` ctp
            JOIN `{$tblPhieu}` ptt ON ctp.MaPhieu = ptt.MaPhieu
            WHERE ptt.MaPhieu = ?
        ";
        $stmt2 = $conn->prepare($sql);
        if (!$stmt2) throw new Exception($conn->error);
        $stmt2->bind_param('i', $maPhieu);
        $stmt2->execute();
        $res = $stmt2->get_result();

        // 3. Cập nhật trạng thái ghế thành "Đã đặt"
        $stmt3 = $conn->prepare("
            UPDATE `{$tblGheSuat}`
            SET TrangThai = 'Đã đặt'
            WHERE MaSuat = ? AND MaGhe = ?
        ");
        if (!$stmt3) throw new Exception($conn->error);

        while ($row = $res->fetch_assoc()) {
            $maGhe = $row['MaGhe'];
            $maSuat = $row['MaSuat'];
            
            $stmt3->bind_param('ii', $maSuat, $maGhe);
            $stmt3->execute();
            
            // Kiểm tra xem có cập nhật được không
            if ($stmt3->affected_rows === 0) {
                error_log("Không thể cập nhật ghế MaSuat={$maSuat}, MaGhe={$maGhe}");
            }
        }
        $stmt3->close();
        $stmt2->close();

        $conn->commit();
        $conn->close();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("xacNhanPhieu lỗi: " . $e->getMessage());
        if (isset($stmt) && is_object($stmt)) $stmt->close();
        if (isset($stmt2) && is_object($stmt2)) $stmt2->close();
        if (isset($stmt3) && is_object($stmt3)) $stmt3->close();
        $conn->close();
        return false;
    }
}


/**
 * Giải phóng ghế quá hạn (nếu phiếu tạm đã trên 10 phút)
 */
function giaiPhongGheHetHan() {
    $conn = connectDatabase();
    if (!$conn) return;

    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);
    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);

    if (!$tblGheSuat || !$tblPhieu) {
        error_log("Bảng ghesuatchieu hoặc phieutamtinh không tồn tại; không thể giải phóng ghế.");
        $conn->close();
        return;
    }

    $sql = "
        UPDATE `{$tblGheSuat}` gs
        JOIN `{$tblPhieu}` ptt ON gs.MaSuat = ptt.MaSuat
        SET gs.TrangThai = 'Trống'
        WHERE ptt.TrangThai = 'Tạm giữ'
          AND TIMESTAMPDIFF(MINUTE, ptt.NgayLap, NOW()) >= 10
    ";
    $conn->query($sql);
    $conn->close();
}

/**
 * Lấy thông tin chi tiết của một phim
 */
function getPhimDetail($maPhim) {
    $conn = connectDatabase();
    if (!$conn) return null;

    $tbl = findExistingTable($conn, ['phim', 'Phim']);
    if (!$tbl) {
        $conn->close();
        return null;
    }

    $sql = "SELECT MaPhim, TenPhim, Poster, MoTaNgan, ThoiLuong, NgayKhoiChieu, TuoiGioiHan, TrangThai, GiaCoBan FROM `{$tbl}` WHERE MaPhim = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return null;
    }
    $stmt->bind_param('i', $maPhim);
    $stmt->execute();
    $result = $stmt->get_result();
    $phim = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
    return $phim;
}

/**
 * Lấy thông tin khách hàng
 */
function getKhachHang($maKH) {
    $conn = connectDatabase();
    if (!$conn) return null;

    $tbl = findExistingTable($conn, ['khachhang', 'KhachHang']);
    if (!$tbl) {
        $conn->close();
        return null;
    }

    $sql = "SELECT MaKH, HoTen, SoDienThoai, NgaySinh, Email, MatKhau FROM `{$tbl}` WHERE MaKH = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return null;
    }
    $stmt->bind_param('i', $maKH);
    $stmt->execute();
    $result = $stmt->get_result();
    $kh = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
    return $kh;
}

/**
 * Hủy phiếu tạm giữ
 */
function huyPhieuTam($maPhieu) {
    $conn = connectDatabase();
    if (!$conn) return false;

    $tblPhieu = findExistingTable($conn, ['phieutamtinh', 'PhieuTamTinh']);
    $tblGheSuat = findExistingTable($conn, ['ghesuatchieu', 'GheSuatChieu']);
    $tblCT = findExistingTable($conn, ['chitietphieu', 'ChiTietPhieu']);

    if (!$tblPhieu || !$tblGheSuat || !$tblCT) {
        $conn->close();
        return false;
    }

    $conn->begin_transaction();
    try {
        // Lấy thông tin suất chiếu và ghế
        $sql = "SELECT MaSuat FROM `{$tblPhieu}` WHERE MaPhieu = ? AND TrangThai = 'Tạm giữ'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $maPhieu);
        $stmt->execute();
        $result = $stmt->get_result();
        $phieu = $result->fetch_assoc();
        $stmt->close();

        if (!$phieu) throw new Exception("Phiếu không tồn tại hoặc không ở trạng thái tạm giữ");

        $maSuat = $phieu['MaSuat'];

        // Cập nhật trạng thái ghế về trống
        $sql = "
            UPDATE `{$tblGheSuat}` gs
            JOIN `{$tblCT}` ct ON gs.MaGhe = ct.MaGhe
            SET gs.TrangThai = 'Trống'
            WHERE ct.MaPhieu = ? AND gs.MaSuat = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $maPhieu, $maSuat);
        $stmt->execute();
        $stmt->close();

        // Xóa chi tiết phiếu
        $stmt = $conn->prepare("DELETE FROM `{$tblCT}` WHERE MaPhieu = ?");
        $stmt->bind_param('i', $maPhieu);
        $stmt->execute();
        $stmt->close();

        // Xóa phiếu
        $stmt = $conn->prepare("DELETE FROM `{$tblPhieu}` WHERE MaPhieu = ?");
        $stmt->bind_param('i', $maPhieu);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        $conn->close();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("huyPhieuTam lỗi: " . $e->getMessage());
        $conn->close();
        return false;
    }
}

/**
 * Tính tuổi từ ngày sinh
 */
function tinhTuoi($ngaySinh) {
    $birthDate = new DateTime($ngaySinh);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

/**
 * Kiểm tra xem khách hàng có đủ tuổi xem phim không
 */
function kiemTraDoTuoiKhachHang($maKH, $maPhim) {
    $khachHang = getKhachHang($maKH);
    $phim = getPhimDetail($maPhim);
    
    if (!$khachHang || !$phim) {
        return false;
    }
    
    $tuoiKH = tinhTuoi($khachHang['NgaySinh']);
    return kiemTraDoTuoiPhim($tuoiKH, $phim['TuoiGioiHan']);
}