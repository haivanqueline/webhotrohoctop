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
    $danhsach_id = $_POST['danhsach'];
    $danhsach_id = filter_var($danhsach_id, FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $thumb_rename = create_unique_id() . '.' . $thumb_ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_folder = '../upload/' . $thumb_rename;

    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $video_rename = create_unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../upload/' . $video_rename;

    $verify_content = $conn->prepare("select * from `content` where giasu_id = ? and title = ? and `desc` = ?");
    $verify_content->execute([$giasu_id, $title, $desc]);

    if ($verify_content->rowCount() > 0) {
        $message[] = 'Bài học này đã có';
    } else {
        if ($thumb_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn';
        } else {
            $add_content = $conn->prepare("insert into `content`(id,giasu_id,danhsach_id,title,`desc`,video,thumbnail,status) values(?,?,?,?,?,?,?,?)");
            $add_content->execute([$id, $giasu_id, $danhsach_id, $title, $desc, $video_rename, $thumb_rename, $status]);
            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            move_uploaded_file($video_tmp_name, $video_folder);
            $message[] = 'Bài học mới đã được tạo';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nội dung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>

    <section class="crud-form">
        <h1 class="heading">Thêm bài học</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <p>Trạng thái bài học <span>*</span></p>
            <select name="status" class="box" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Không hoạt động">Không hoạt động</option>
            </select>
            <p>Tiêu đề bài học <span>*</span></p>
            <input type="text" name="title" placeholder="Nhập tiêu đề" class="box" maxlength="100" required>
            <p>Nội dung bài học <span>*</span></p>
            <textarea name="desc" required cols="30" rows="10" class="box" placeholder="Nhập tiêu đề bài học" maxlength="1000"></textarea>
            <select name="danhsach" class="box" required>
                <option value="" disabled selected>Chọn danh sách</option>
                <?php
                $select_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ?");
                $select_danhsach->execute([$giasu_id]);
                if ($select_danhsach->rowCount() > 0) {
                    while ($fetch_danhsach = $select_danhsach->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <option value="<?= $fetch_danhsach['id'] ?>"><?= $fetch_danhsach['title'] ?></option>
                <?php
                    }
                } else {
                    echo '<option value="" disabled>Không có danh sách nào được tạo hết!</option>';
                }
                ?>
            </select>
            <p>Chọn ảnh chủ đề bài học <span>*</span></p>
            <input type="file" name="thumb" required accept="image/*" class="box">
            <p>Chọn video bài học<span>*</span></p>
            <input type="file" name="video" required accept="video/*" class="box">
            <input type="submit" value="Thêm bài học" name="submit" class="bt">
        </form>
    </section>

    <script src="../admin.js"></script>
</body>

</html>