<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>db_look</title>
</head>
<body>
    <?php
        // DB接続設定
        $dsn = 'mysql:dbname=tb260291db;host=localhost';
        $user = 'tb-260291';
        $password = 'uJmur5hhae';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = 'SELECT * FROM m5_db';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(); 
    ?>
    
    <h2>users</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>username</th>
                <th>password</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = 'SELECT * FROM users';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
            
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['password']) . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
    
    <h2>過去の投稿</h2>
    <table border="1">
        <thead>
            <tr>
                <th>投稿番号</th>
                <th>名前</th>
                <th>コメント</th>
                <th>日時</th>
                <th>ユーザーID</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = 'SELECT * FROM info';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
            
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['postingNumber']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['comment']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['dateTime']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['userId']) . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</body>
</html>