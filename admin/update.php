<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_POST['submit'])) {
    $select_giasu = $conn->prepare("select * from `giasu` where id = ? limit 1");
    $select_giasu->execute([$giasu_id]);
    $fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC);


    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $job = $_POST['job'];
    $job = filter_var($job, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    if (!empty($name)) {
        $update_name = $conn->prepare("update `giasu` set name = ? where id = ?");
        $update_name->execute([$name, $giasu_id]);
        $message[] = 'Cập nhập tên thành công';
    }

    if (!empty($job)) {
        $update_job = $conn->prepare("update `giasu` set job = ? where id = ?");
        $update_job->execute([$job, $giasu_id]);
        $message[] = 'Cập nhập nghề nghiệp thành công';
    }

    if (!empty($email)) {
        $select_giasu_email = $conn->prepare("select * from `giasu` where email = ?");
        $select_giasu_email->execute([$email]);
        if ($select_giasu_email->rowCount() > 0) {
            $message[] = 'Email này đã được dùng!';
        } else {
            $update_email = $conn->prepare("update `giasu` set email = ? where id = ?");
            $update_email->execute([$email, $giasu_id]);
            $message[] = 'Cập nhập email thành công';
        }
    }

    $prev_image = $fetch_giasu['image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../upload/' . $rename;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Ảnh đại diện không phù hợp';
        } else {
            $update_image = $conn->prepare("update `giasu` set image = ? where id = ?");
            $update_image->execute([$rename, $giasu_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if ($prev_image != '' and $prev_image != $rename) {
                unlink('../upload/' . $prev_image);
            }
            $message[] = 'Cập nhập ảnh đại diện thành công!';
        }
    }


    $default_password = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_password = $fetch_giasu['password'];
    $opassword = sha1($_POST['opassword']);
    $opassword = filter_var($opassword, FILTER_SANITIZE_STRING);
    $npassword = sha1($_POST['npassword']);
    $npassword = filter_var($npassword, FILTER_SANITIZE_STRING);
    $cpassword = sha1($_POST['cpassword']);
    $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);
    if ($opassword != $default_password) {
        if ($opassword != $prev_password) {
            $message[] = 'Mật khẩu cũ không đúng!';
        } elseif ($npassword != $cpassword) {
            $message[] = 'Mật khẩu xác nhận không giống nhau!';
        } else {
            if ($npassword != $default_password) {
                $update_password = $conn->prepare("update `giasu` set password = ? where id = ?");
                $update_password->execute([$cpassword, $giasu_id]);
                $message[] = 'Cập nhập mật khẩu thành công';
            } else {
                $message[] = 'Hãy nhập mật khẩu mới';
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
    <title>Cập nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Cập nhập hồ sơ</h3>
            <div class="flex">
                <div class="col">
                    <p>Tên của bạn </p>
                    <input type="text" name="name" maxlength="50" class="box" placeholder="<?= $fetch_profile['name']; ?>">
                    <p>Nghề nghiệp của bạn </p>
                    <select name="job" class="box">
                        <option value="" selected><?= $fetch_profile['job']; ?></option>
                        <option value="Lập trình viên">Lập trình viên</option>
                        <option value="Thiết kế">Thiết kế</option>
                        <option value="Giáo viên">Giáo viên</option>
                        <option value="Kĩ sư">Kĩ sư</option>
                        <option value="Bác sĩ">Bác sĩ</option>
                        <option value="Nhiếp ảnh gia">Nhiếp ảnh gia</option>
                        <option value="Công nhân">Công nhân</option>
                    </select>
                    <p>Địa chỉ email của bạn</p>
                    <input type="email" name="email" maxlength="50" class="box" placeholder="<?= $fetch_profile['email']; ?>">
                </div>
                <div class="col">
                    <p>Mật khẩu cũ của bạn</p>
                    <input type="password" name="opassword" maxlength="20" class="box" placeholder="Nhập mật khẩu cũ của bạn">
                    <p>Mật khẩu mới của bạn</p>
                    <input type="password" name="npassword" maxlength="20" class="box" placeholder="Nhập mật khẩu mới của bạn">
                    <p>Xác nhận mật khẩu mới của bạn</p>
                    <input type="password" name="cpassword" maxlength="20" class="box" placeholder="Xác nhận mật khẩu mới của bạn">
                </div>
            </div>
            <p>Chọn ảnh đại diện </p>
            <input type="file" name="image" class="box" accept="image/*">
            <input type="submit" value="Cập nhập ngay" name="submit" class="bt">
        </form>
    </section>
    <script src="../admin.js"></script>
</body>

</html>