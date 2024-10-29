<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>db_create</title>
</head>
<body>
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname=tb260291db;host=localhost';
        $user = 'tb-260291';
        $password = 'uJmur5hhae';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        // テーブル作成SQL
        $sql = "CREATE TABLE IF NOT EXISTS users"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "username VARCHAR(255) UNIQUE NOT NULL,"
        . "password VARCHAR(255) NOT NULL"
        .");"; 
        
        // テーブル作成SQL(m5-04用)
        $sql = "CREATE TABLE IF NOT EXISTS info"
        . " ("
        . "postingNumber INT AUTO_INCREMENT PRIMARY KEY,"
        . "name VARCHAR(255),"
        . "comment TEXT,"
        . "dateTime DATETIME,"
        . "userId INT,"
        . "FOREIGN KEY (userId) REFERENCES users(id)"
        . ");";
    
        // SQL実行
        $stmt = $pdo->query($sql);
    ?>
</body>
</html>