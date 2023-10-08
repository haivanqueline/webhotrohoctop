<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:danhsach.php');
}
if (isset($_POST['update'])) {


    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $desc = $_POST['desc'];
    $desc = filter_var($desc, FILTER_SANITIZE_STRING);

    $update_danhsach = $conn->prepare("update `danhsach` set title = ?, `desc` = ?, status = ? where id =?");
    $update_danhsach->execute([$title, $desc, $status, $get_id]);
    $message[] = 'Cập nhập danh sách thành công';

    $old_thumb = $_POST['old_thumb'];
    $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_folder = '../upload/' . $rename;
    if (!empty($thumb)) {
        if ($thumb_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn';
        } else {
            $update_thumb = $conn->prepare("update `danhsach` set thumbnail = ? where id = ?");
            $update_thumb->execute([$rename, $get_id]);
            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            if ($old_thumb != '' and $old_thumb != $rename) {
                unlink('../upload/' . $old_thumb);
            }
        }
    }
}
if (isset($_POST['delete_danhsach'])) {

    $verify_danhsach = $conn->prepare("select * from `danhsach` where id = ?");
    $verify_danhsach->execute([$get_id]);
    if ($verify_danhsach->rowCount() > 0) {
        $fetch_thumb = $verify_danhsach->fetch(PDO::FETCH_ASSOC);
        $prev_thumb = $fetch_thumb['thumbnail'];
        if ($prev_thumb != '' && file_exists('../uploads/' . $prev_thumb)) {
            unlink('../uploads/' . $prev_thumb);
        }
        $delete_bookmark = $conn->prepare("delete from `bookmark` where danhsach_id = ?");
        $delete_bookmark->execute([$get_id]);
        $delete_danhsach = $conn->prepare("delete from `danhsach` where id = ?");
        $delete_danhsach->execute([$get_id]);
        header('location:danhsach.php');
    } else {
        $message[] = 'Danh sách đã được xoá!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhập danh sách</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="crud-form">
        <h1 class="heading">Chỉnh sửa danh sách</h1>
        <?php
        $select_danhsach = $conn->prepare("select * from `danhsach` where id = ?");
        $select_danhsach->execute([$get_id]);
        if ($select_danhsach->rowCount() > 0) {
            while ($fetch_danhsach = $select_danhsach->fetch(PDO::FETCH_ASSOC)) {
                $danhsach_id = $fetch_danhsach['id'];
        ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="old_thumb" value="<?= $fetch_danhsach['thumbnail']; ?>">
                    <p>Trạng thái danh sách</p>
                    <select name="status" class="box" required>
                        <option value="<?= $fetch_danhsach['status']; ?>" selected><?= $fetch_danhsach['status']; ?></option>
                        <option value="Hoạt động">Hoạt động</option>
                        <option value="Không hoạt động">Không hoạt động</option>
                    </select>
                    <p>Cập nhập tiêu đề</p>
                    <input type="text" name="title" placeholder="Nhập tiêu đề" class="box" required maxlength="100" value="<?= $fetch_danhsach['title']; ?>">
                    <p>Cập nhập nội dung</p>
                    <textarea name="desc" cols="30" rows="10" class="box" required placeholder="Nhập tiêu đề danh sách" maxlength="1000"><?= $fetch_danhsach['desc']; ?></textarea>
                    <p>Cập nhập ảnh chủ đề</p>
                    <img src="../upload/<?= $fetch_danhsach['thumbnail']; ?>" alt="">
                    <input type="file" name="thumb" accept="image/*" class="box">
                    <input type="submit" value="Cập nhập danh sách" name="update" class="bt">
                    <div class="flex-bt">
                        <input type="submit" value="Xoá danh sách" name="delete_danhsach" class="delete-bt">
                        <a href="viewdanhsach.php?get_id=<?= $danhsach_id; ?>" class="option-bt">Xem danh sách</a>
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">Không có danh sách nào hết!</p>';
        }
        ?>
    </section>

    <script src="../admin.js"></script>
</body>

</html>