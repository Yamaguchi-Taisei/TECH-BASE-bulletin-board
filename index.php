<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;900&display=swap');

body {
    background-color: #5f7fa3;
    margin: 0;
}

h1 {
    font-family: "M PLUS Rounded 1c", sans-serif;
    font-weight: 900;
    font-style: normal;
    color: #ffffff;
    text-align: center;
    margin-top: 20px;
    margin-bottom: 0px;
}

h3 {
    font-family: "M PLUS Rounded 1c", sans-serif;
    font-weight: 400;
    font-style: normal;
}

.container {
    width: 800px;
    background-color: #f7f7f7;
    padding-top: 20px;
    padding-left: 120px;
    padding-right: 120px;
    padding-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
}

/* フォーム */
.form {
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* 投稿 */
.post {
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;
    background-color: #ffffff;
    border-radius: 4px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.post .post_name {
    margin: 0;
    color: #2990ff;
}

.post .post_comment {
    margin: 0;
    font-size: 15px;
}

.post .post_info {
    margin-bottom: 0px;
    font-size: 12px;
    color: #969696;
}

    </style>
  </head>
  <body>

    <?php
    session_start();
    
    // ユーザーがログインしているか確認
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // ログインページへリダイレクト
        exit;
    }
    
    // database.php接続
    include 'database.php';
    ?>
    
    <h1>掲示板</h1>

    <div class="container">
        
        <!--フォーム-->
        <div class="form">
            
            <!--新規投稿フォーム(デフォルト)　｜　編集フォーム(編集番号送信された場合)-->
            <h3><?php echo isset($editComment) && !empty($editComment) ? '編集フォーム' : '新規作成フォーム'; ?></h3>
            <form action="" method="post">
                <div class="mb-1">
                    <label for="nameInput" class="form-label">名前</label>
                    <input type="text" class="form-control" id="nameInput" name="name" placeholder="名前を入力してください"
                        value="<?php echo isset($editName) ? htmlspecialchars($editName) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="commentInput" class="form-label">コメント</label>
                    <textarea class="form-control" id="commentInput" name="comment" placeholder="テキストを入力してください" rows="4"><?php echo isset($editComment) ? htmlspecialchars($editComment) : ''; ?></textarea>
                </div>
                <input type="hidden" name="editId" value="<?php echo isset($editId) ? htmlspecialchars($editId) : ''; ?>">
                <button type="submit" name="submit1" class="btn btn-primary w-100">送信</button>
            </form>
            
            <!--編集番号送信ボタンと、削除番号送信ボタン-->
            <div class="d-flex gap-2 mt-2 mb-2">
                <button type="button" class="btn btn-outline-warning w-50" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-outline-danger w-50" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <!--ログ表示-->
            <?php if (isset($message)) {
                echo '<p>' . htmlspecialchars($message) . '</p>';
            } ?>
        </div>
        
        
        
        <!--過去の投稿一覧表示-->
        <?php foreach ($results as $row): ?>
            <div class="post">
                <p class="post_name"><?php echo htmlspecialchars($row['name']); ?>さん</p>
                <p class="post_comment"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></p>
                <p class="post_info">
                    投稿番号: <?php echo htmlspecialchars($row['postingNumber']); ?> | 
                    日時: <?php echo htmlspecialchars($row['dateTime']); ?> | 
                    投稿者: <?php echo htmlspecialchars($row['username']); ?>
                </p>
            </div>
        <?php endforeach; ?>
        
        <!--ログイン者と、ログアウト機能-->
        <?php
            // ユーザー名の最初の文字を取得
            $initial = mb_substr($_SESSION['username'], 0, 1);
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; font-size: 18px;">
                    <?php echo htmlspecialchars($initial); ?>
                </div>
                <span class="ms-2"><?php echo htmlspecialchars($_SESSION['username']); ?>さん ログイン中</span>
            </div>
            <a href="logout.php" class="btn btn-outline-secondary">ログアウト</a>
        </div>

    </div>
    
    <!-- 編集モーダル -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">編集番号送信フォーム</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="mb-3">
                <label for="editNumber" class="form-label">編集する投稿番号</label>
                <input type="text" class="form-control" id="editNumber" name="editNumber" placeholder="編集したい投稿番号を入力してください">
              </div>
              <button type="submit" class="btn btn-warning" name="submit3">送信</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- 削除モーダル -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">削除フォーム</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="mb-3">
                <label for="deleteNumber" class="form-label">削除する投稿番号</label>
                <input type="text" class="form-control" id="deleteNumber" name="deleteNumber" placeholder="削除したい投稿番号を入力してください">
              </div>
              <button type="submit" class="btn btn-danger" name="submit2">削除</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
