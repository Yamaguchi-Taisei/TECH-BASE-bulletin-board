<?php
    // DB接続設定
    $dsn = 'mysql:dbname=tb260291db;host=localhost';
    $user = 'tb-260291';
    $password = 'uJmur5hhae';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // 新規登録処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit1'])) {
        if (!empty($_POST['name']) && !empty($_POST['comment'])) {
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $userId = $_SESSION['user_id']; // ログイン中のユーザーIDを取得
    
            // 編集対象IDが存在する場合は編集処理
            if (!empty($_POST['editId'])) {
                $editId = $_POST['editId'];
                $sql = "UPDATE info SET name = :name, comment = :comment, dateTime = NOW() WHERE postingNumber = :id AND userId = :userId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
    
                if ($stmt->rowCount() > 0) {
                    $message = '編集成功';
                } else {
                    $message = 'ERROR: 自分の投稿ではないため、編集できません';
                }
            } else {
                $sql = "INSERT INTO info (name, comment, dateTime, userId) VALUES (:name, :comment, NOW(), :userId)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->execute();
                $message = '書き込み成功';
            }
        } else {
            $message = 'ERROR：どちらかの項目が、空欄です';
        }
    }
    
    // 削除操作
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
        if (!empty($_POST['deleteNumber']) && is_numeric($_POST['deleteNumber'])) {
            $id = $_POST['deleteNumber'];
            $userId = $_SESSION['user_id'];
    
            $sql = 'DELETE FROM info WHERE postingNumber = :id AND userId = :userId';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $message = "削除成功";
            } else {
                $message = "ERROR: 自分の投稿ではないため、削除できません";
            }
        } else {
            $message = "ERROR: 削除する投稿番号を入力してください";
        }
    }
    
    // 編集操作
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit3'])) {
        if (!empty($_POST['editNumber']) && is_numeric($_POST['editNumber'])) {
            $id = $_POST['editNumber'];
            $userId = $_SESSION['user_id'];
    
            $sql = 'SELECT name, comment FROM info WHERE postingNumber = :id AND userId = :userId';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($editRow) {
                $editName = $editRow['name'];
                $editComment = $editRow['comment'];
                $editId = $id;
            } else {
                $message = "ERROR: 自分の投稿ではないため、編集できません";
            }
        } else {
            $message = "ERROR: 編集する投稿番号を入力してください";
        }
    }
    
    // 投稿の取得（infoテーブルとusersテーブルを結合）
    $sql = 'SELECT info.postingNumber, info.name, info.comment, info.dateTime, users.username 
            FROM info 
            INNER JOIN users ON info.userId = users.id 
            ORDER BY info.postingNumber DESC';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
