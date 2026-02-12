<?php
// Railway بيدي البيانات دي أوتوماتيك في البيئة بتاعته
$host = getenv('MYSQLHOST') ?: 'localhost';
$dbname = getenv('MYSQLDATABASE') ?: 'ihubgcc';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    // ضفنا البورت والمتغيرات الديناميكية
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // نصيحة: للعميل يفضل تظهر رسالة بسيطة، وللبحث عن الأخطاء سيبها كده
    die("Connection failed: " . $e->getMessage());
}
?>