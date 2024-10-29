<?php
    session_start();
    
    // DB接続設定
    $dsn = 'mysql:dbname=tb260291db;host=localhost';
    $user = 'tb-260291';
    $password = 'uJmur5hhae';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                $message = 'ERROR: ユーザ名またはパスワードが正しくありません。';
            }
        } else {
            $message = 'ERROR: ユーザ名またはパスワードが空です。';
        }
    }
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログイン</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;900&display=swap');
    
        body, html {
            height: 100%;
            margin: 0;
            background-color: #5f7fa3;
        }
        
        h3 {
            font-family: "M PLUS Rounded 1c", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .container-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* 画面全体の高さ */
        }

        .container {
            width: 600px;
            background-color: #f7f7f7;
            padding: 20px 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        i {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="container">
            <h3>ログイン<i class="bi bi-box-arrow-in-left"></i></h3>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">ユーザ名</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="ユーザ名を入力してください">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="パスワードを入力してください">
                </div>
                
                <div class="d-flex gap-2 mt-2 mb-2">
                    <button type="submit" class="btn btn-primary w-50" name="login">
                        ログイン<i class="bi bi-box-arrow-in-left"></i>
                    </button>
                    <a href="register.php" class="btn btn-primary w-50 text-center">
                        アカウント登録はこちら<i class="bi bi-person-circle"></i>
                    </a>
                </div>
            </form>
            <?php if (isset($message)) {
                echo '<p>' . htmlspecialchars($message) . '</p>';
            } ?>
        </div>
    </div>
</body>
</html>
