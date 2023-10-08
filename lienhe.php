<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $verify_lienhe = $conn->prepare("select * from `lienhe` where name = ? and email=? and number=? and mess = ?");
    $verify_lienhe->execute([$name, $email, $number, $msg]);
    if ($verify_lienhe->rowCount() > 0) {
        $message[] = 'Lời phản hồi của bạn đã được gửi đi!';
    } else {
        $send_message = $conn->prepare("insert into `lienhe`(name, email, number, mess) values(?,?,?,?)");
        $send_message->execute([$name, $email, $number, $msg]);
        $message[]='Lời phản hồi của bạn đã được gửi thành công!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Liên hệ chúng tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="lienhe">
        <div class="row">
            <div class="image">
                <img src="logohondai.ico" alt="">
            </div>
            <form action="" method="post">
                <h3>Liên hệ với chúng tôi</h3>
                <input type="text" name="name" class="box" required maxlength="50" placeholder="Nhập tên của bạn">
                <input type="email" name="email" class="box" required maxlength="50" placeholder="Nhập email của bạn">
                <input type="number" name="number" class="box" required maxlength="50" placeholder="Nhập số điện thoại của bạn" min="0" max="9999999999">
                <textarea name="msg" class="box" required maxlength="500" placeholder="Nhập nội dung của bạn" cols="30" rows="10"></textarea>
                <input type="submit" value="Gửi phản hồi" class="inline-bt" name="submit">
            </form>
        </div>
        <div class="box-container">
            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>Số điện thoại</h3>
                <a href="tel:0905087642">0905087642</a>
                <a href="tel:0905087642">0905087642</a>
                <a href="tel:0905087642">0905087642</a>
            </div>
            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>Địa chỉ email</h3>
                <a href="mailto:haivanqueline@gmail.com">haivanqueline@gmail.com</a>
                <a href="mailto:ngoquangy2002@gmail.com">ngoquangy2002@gmail.com</a>
                <a href="mailto:nguyen202202@gmail.com">nguyen202202@gmail.com</a>
            </div>
            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Địa chỉ thường trú</h3>
                <a href="#">567 Lê Duẩn, Phường Eatam, Thành phố Buôn Ma Thuật, Tỉnh Đắk Lắk</a>
            </div>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>