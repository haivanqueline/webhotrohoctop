<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $desc = $_POST['desc'];
    $desc = filter_var($desc, FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_folder = '../upload/' . $rename;

    $verify_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ? and title = ? and `desc` = ?");
    $verify_danhsach->execute([$giasu_id, $title, $desc]);

    if ($verify_danhsach->rowCount() > 0) {
        $message[] = 'Danh sách này đã có';
    } else {
        $add_danhsach = $conn->prepare("insert into `danhsach`(id,giasu_id,title,`desc`,thumbnail,status) values(?,?,?,?,?,?)");
        $add_danhsach->execute([$id, $giasu_id, $title, $desc, $rename, $status]);
        move_uploaded_file($thumb_tmp_name, $thumb_folder);
        $message[] = 'Danh sách mới đã được tạo';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh sách</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="crud-form">
        <h1 class="heading">Thêm danh sách</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <p>Trạng thái danh sách <span>*</span></p>
            <select name="status" class="box" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Không hoạt động">Không hoạt động</option>
            </select>
            <p>Tiêu đề danh sách <span>*</span></p>
            <input type="text" name="title" placeholder="Nhập tiêu đề" class="box" maxlength="100" required>
            <p>Nội dung danh sách <span>*</span></p>
            <textarea name="desc" required cols="30" rows="10" class="box" placeholder="Nhập tiêu đề danh sách" maxlength="1000"></textarea>
            <p>Ảnh chủ đề danh sách <span>*</span></p>
            <input type="file" name="thumb" required accept="image/*" class="box">
            <input type="submit" value="Tạo danh sách" name="submit" class="bt">
        </form>
    </section>

    <script src="../admin.js"></script>
</body>

</html>