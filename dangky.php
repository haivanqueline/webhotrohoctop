<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}
if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $cpassword = sha1($_POST['cpassword']);
    $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'upload/' . $rename;
    $select_user_email = $conn->prepare("select * from `user` where email = ?");
    $select_user_email->execute([$email]);
    if ($select_user_email->rowCount() > 0) {
        $message[] = 'Email này đã được đăng ký';
    } else {
        if ($password != $cpassword) {
            $message[] = 'Mật khẩu không giống nhau!';
        } else {
            if ($image_size > 2000000) {
                $message[] = 'Dung lượng hoặc ảnh không phù hợp!';
            } else {
                $insert_user = $conn->prepare("insert into `user`(id, name, email, password, image) values(?,?,?,?,?)");
                $insert_user->execute([$id, $name, $email, $cpassword, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $verify_user = $conn->prepare("select * from `user` where email = ? and password = ? limit 1");
                $verify_user->execute([$email, $cpassword]);
                $row = $verify_user->fetch(PDO::FETCH_ASSOC);

                if ($verify_user->rowCount() > 0) {
                    setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                    header('location:home.php');
                } else {
                    $message[] = 'Lỗi rồi ní ơi!';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Đăng ký</h3>
            <div class="flex">
                <div class="col">
                    <p>Tên của bạn <span>*</span></p>
                    <input type="text" name="name" maxlength="50" required class="box" placeholder="Nhập tên của bạn">
                    <p>Địa chỉ email của bạn<span>*</span></p>
                    <input type="email" name="email" maxlength="50" required class="box" placeholder="Nhập địa chỉ email của bạn">
                </div>
                <div class="col">
                    <p>Mật khẩu của bạn<span>*</span></p>
                    <input type="password" name="password" maxlength="20" required class="box" placeholder="Nhập mật khẩu của bạn">
                    <p>Xác nhận mật khẩu của bạn<span>*</span></p>
                    <input type="password" name="cpassword" maxlength="20" required class="box" placeholder="Xác nhận mật khẩu của bạn">
                </div>
            </div>
            <p>Chọn ảnh đại diện <span>*</span></p>
            <input type="file" name="image" class="box" required accept="image/*">
            <input type="submit" value="Đăng ký ngay" name="submit" class="bt">
        </form>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>