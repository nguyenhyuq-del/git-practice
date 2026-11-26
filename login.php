<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

extract((new AdminLoginPageController())->login(), EXTR_OVERWRITE);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin - TT SHOP</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef5f1;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 420px;
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        h1 {
            text-align: center;
            color: #15985d;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            height: 48px;
            padding: 0 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 7px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 8px;
            background: #15985d;
            color: white;
            font-size: 18px;
            font-weight: bold;
            margin-top: 25px;
            cursor: pointer;
        }

        button:hover {
            background: #0d7d4b;
        }

        .error {
            background: #ffe2e2;
            color: red;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">

<div class="login-box">
    <h1>TT SHOP ADMIN</h1>

    <?php if (!empty($error)) { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST">
        <label>Tài khoản</label>
        <input type="text" name="username" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>