<?php
// Cấu hình kết nối cơ sở dữ liệu
$servername = "localhost";  
$username = "root";         
$password = "";            
$dbname = "webbanhang";   


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
