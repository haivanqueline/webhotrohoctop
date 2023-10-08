<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $verify_user = $conn->prepare("select * from `user` where email = ? and password = ? limit 1");
    $verify_user->execute([$email, $password]);
    $row = $verify_user->fetch(PDO::FETCH_ASSOC);

    if ($verify_user->rowCount() > 0) {
        setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
        header('location:home.php');
    } else {
        $message[] = 'Sai mật khẩu hoặc tài khoản';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Đăng nhập</h3>
            <p>Địa chỉ email của bạn<span>*</span></p>
            <input type="email" name="email" maxlength="50" required class="box" placeholder="Nhập địa chỉ email của bạn">
            <p>Mật khẩu của bạn<span>*</span></p>
            <input type="password" name="password" maxlength="20" required class="box" placeholder="Nhập mật khẩu của bạn">
            <input type="submit" value="Đăng nhập ngay" name="submit" class="bt">
        </form>
    </section>
    


    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>