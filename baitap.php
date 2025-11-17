<!DOCTYPE html>
<html>
    <head>
        <title> L.6.2.1</title>
        <meta charset="UTF-8">
</head>
<body>
    <h1> Goodbye! </h1>
    <?php
    echo "<p>Biểu diễn nhị phân của 9876:";
    echo decbin(9876);
    echo "</p><input type='button' value = 'Submit'>";
    ?>
    </body>
    </html>

<br> 

     ví dụ 1
    <?php
$i = 1;
do {
    echo $i;
    $i++;
} while ($i < 10);
?>

    <br>
    ví dụ 2
    <?php
$x=3;
if($x>5){
    echo "x lớn hơn 5";
}
else{
    echo "x nhỏ hơn 5";
}
?>
    <br>
    ví dụ 3
    <?php 
    $x=4;
    switch($x){
        case 1:
            echo "x = 1";
            break;
        case 2:
            echo "x = 2";
            break;
        case 3:
            echo "x = 3";
            break;
        default:
            echo "không tìm được giá trị phù hợp";
    }?>

    <br>
    ví dụ 4
    <?php
    for ($i=1; $i<10; $i++){
        echo $i;
    }
    ?>

    <br>
    ví dụ 5
    <?php
    $mang=array("Đào"," Cam"," Táo");
    foreach($mang as $giatri){
        echo $giatri;

    }
    ?>
    <br>


