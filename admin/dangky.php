<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
}
if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $job = $_POST['job'];
    $job = filter_var($job, FILTER_SANITIZE_STRING);
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
    $image_folder = '../upload/' . $rename;
    $select_giasu_email = $conn->prepare("select * from `giasu` where email = ?");
    $select_giasu_email->execute([$email]);
    if ($select_giasu_email->rowCount() > 0) {
        $message[] = 'Email này đã được đăng ký';
    } else {
        if ($password != $cpassword) {
            $message[] = 'Mật khẩu không giống nhau!';
        } else {
            if ($image_size > 2000000) {
                $message[] = 'Dung lượng hoặc ảnh không phù hợp!';
            } else {
                $insert_giasu = $conn->prepare("insert into `giasu`(id, name, job, email, password, image) values(?,?,?,?,?,?)");
                $insert_giasu->execute([$id, $name, $job, $email, $cpassword, $rename]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $verify_giasu = $conn->prepare("select * from `giasu` where email = ? and password = ? limit 1");
                $verify_giasu->execute([$email, $cpassword]);
                $row = $verify_giasu->fetch(PDO::FETCH_ASSOC);

                if ($verify_giasu->rowCount() > 0) {
                    setcookie('giasu_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                    header('location:dashboard.php');
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
    <title>Đăng ký</title>
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
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Đăng ký</h3>
            <div class="flex">
                <div class="col">
                    <p>Tên của bạn <span>*</span></p>
                    <input type="text" name="name" maxlength="50" required class="box" placeholder="Nhập tên của bạn">
                    <p>Nghề nghiệp của bạn <span>*</span></p>
                    <select name="job" class="box">
                        <option value="" disabled selected>Nghề nghiệp hiện tại của bạn</option>
                        <option value="Lập trình viên">Lập trình viên</option>
                        <option value="Thiết kế">Thiết kế</option>
                        <option value="Giáo viên">Giáo viên</option>
                        <option value="Kĩ sư">Kĩ sư</option>
                        <option value="Bác sĩ">Bác sĩ</option>
                        <option value="Nhiếp ảnh gia">Nhiếp ảnh gia</option>
                        <option value="Công nhân">Công nhân</option>
                    </select>
                    <p>Địa chỉ email của bạn<span>*</span></p>
                    <input type="email" name="email" maxlength="50" required class="box" placeholder="Nhập địa chỉ email của bạn">
                </div>
                <div class="col">
                    <p>Mật khẩu của bạn<span>*</span></p>
                    <input type="password" name="password" maxlength="20" required class="box" placeholder="Nhập mật khẩu của bạn">
                    <p>Xác nhận mật khẩu của bạn<span>*</span></p>
                    <input type="password" name="cpassword" maxlength="20" required class="box" placeholder="Xác nhận mật khẩu của bạn">
                    <p>Chọn ảnh đại diện <span>*</span></p>
                    <input type="file" name="image" class="box" required accept="image/*">
                </div>
            </div>
            <input type="submit" value="Đăng ký ngay" name="submit" class="bt">
            <p class="link">Đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay!</a></p>
        </form>
    </section>






    <script src="../admin.js"></script>
</body>

</html>