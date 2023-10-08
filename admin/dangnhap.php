<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
}
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $verify_giasu = $conn->prepare("select * from `giasu` where email = ? and password = ? limit 1");
    $verify_giasu->execute([$email, $password]);
    $row = $verify_giasu->fetch(PDO::FETCH_ASSOC);

    if ($verify_giasu->rowCount() > 0) {
        setcookie('giasu_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
        header('location:dashboard.php');
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
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body style="padding-left: 0;">

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message form">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
    }
    ?>
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data" class="login">
            <h3>Đăng nhập</h3>
            <p>Địa chỉ email của bạn<span>*</span></p>
            <input type="email" name="email" maxlength="50" required class="box" placeholder="Nhập địa chỉ email của bạn">
            <p>Mật khẩu của bạn<span>*</span></p>
            <input type="password" name="password" maxlength="20" required class="box" placeholder="Nhập mật khẩu của bạn">
            <input type="submit" value="Đăng nhập ngay" name="submit" class="bt">
            <p class="link">Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay!</a></p>
        </form>
    </section>






    <script src="../admin.js"></script>
</body>

</html>