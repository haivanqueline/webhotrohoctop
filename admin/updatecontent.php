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
    $danhsach_id = $_POST['danhsach'];
    $danhsach_id = filter_var($danhsach_id, FILTER_SANITIZE_STRING);

    $update_content = $conn->prepare("update `content` set title = ?, `desc` = ?, status = ? where id = ?");
    $update_content->execute([$title, $desc, $status, $get_id]);

    if (!empty($danhsach_id)) {
        $update_danhsach = $conn->prepare("update `content` set danhsach_id= ? where id = ?");
        $update_danhsach->execute([$danhsach_id, $get_id]);
    }

    $old_thumb = $_POST['old_thumbnail'];
    $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $thumb_rename = create_unique_id() . '.' . $thumb_ext;
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_folder = '../upload/' . $thumb_rename;

    if (!empty($thumb)) {
        if ($thumb_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn';
        } else {
            $update_thumb = $conn->prepare("update `content` set thumbnail = ? where id = ?");
            $update_thumb->execute([$thumb_rename, $get_id]);
            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            if ($old_thumb != '') {
                unlink('../upload/' . $old_thumb);
            }
        }
    }
    
    $old_video = $_POST['old_video'];
    $old_video = filter_var($old_video, FILTER_SANITIZE_STRING);
    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $video_rename = create_unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../upload/' . $video_rename;

    if (!empty($video)) {
        $update_video = $conn->prepare("update `content` set video = ? where id = ?");
        $update_video->execute([$video_rename, $get_id]);
        move_uploaded_file($video_tmp_name, $video_folder);
        if ($old_video != '') {
            unlink('../upload/' . $old_video);
        }
    }
    $message[] = 'Cập nhập bài học thành công';
}
if (isset($_POST['delete_content'])) {
    $delete_id = $_POST['content_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
    $verify_content = $conn->prepare("select * from `content` where id = ?");
    $verify_content->execute([$delete_id]);
    if ($verify_content->rowCount()>0) {
        $fetch_content = $verify_content->fetch(PDO::FETCH_ASSOC);
        unlink('../upload/'.$fetch_content['thumbnail']);
        unlink('../upload/'.$fetch_content['video']);
        $delete_comment = $conn->prepare("delete from `comments` where content_id = ?");
        $delete_comment->execute([$delete_id]);
        $delete_likes = $conn->prepare("delete from `likes` where content_id = ?");
        $delete_likes->execute([$delete_id]);
        $delete_content = $conn->prepare("delete from `content` where id = ?");
        $delete_content->execute([$delete_id]);
        header('location:contents.php');
    }else {
        $message[]='Bài học đã được xóa';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhập nội dung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>

    <section class="crud-form">
        <h1 class="heading">Cập nhập bài học</h1>
        <?php
        $select_content = $conn->prepare("select * from `content` where id=?");
        $select_content->execute([$get_id]);
        if ($select_content->rowCount() > 0) {
            while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                    <input type="hidden" name="old_video" value="<?= $fetch_content['video']; ?>">
                    <input type="hidden" name="old_thumbnail" value="<?= $fetch_content['thumbnail']; ?>">
                    <p>Trạng thái bài học</p>
                    <select name="status" class="box" required>
                        <option value="<?= $fetch_content['status']; ?>" selected><?= $fetch_content['status']; ?></option>
                        <option value="Hoạt động">Hoạt động</option>
                        <option value="Không hoạt động">Không hoạt động</option>
                    </select>
                    <p>Tiêu đề bài học</p>
                    <input type="text" name="title" placeholder="Nhập tiêu đề" class="box" maxlength="100" value="<?= $fetch_content['title']; ?>" required>
                    <p>Nội dung bài học</p>
                    <textarea name="desc" required cols="30" rows="10" class="box" placeholder="Nhập tiêu đề bài học" maxlength="1000"><?= $fetch_content['desc']; ?></textarea>
                    <select name="danhsach" class="box">
                        <option value="<?= $fetch_content['danhsach_id']; ?>" selected>Chọn danh sách</option>
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
                    <p>Cập nhập ảnh chủ đề bài học</p>
                    <img src="../upload/<?= $fetch_content['thumbnail']; ?>" alt="">
                    <input type="file" name="thumb" accept="image/*" class="box">
                    <p>Cập nhập video bài học<span>*</span></p>
                    <video src="../upload/<?= $fetch_content['video']; ?>" class="media" controls></video>
                    <input type="file" name="video" accept="video/*" class="box">
                    <input type="submit" value="Cập nhập bài học" name="update" class="bt">
                    <div class="flex-bt">
                        <a href="viewcontent.php?get_id=<?= $get_id; ?>" class="option-bt">
                            Xem bài học
                        </a>
                        <input type="submit" value="Xóa bài học" name="delete_content" class="delete-bt">
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">Không tìm thấy bài học nào hết!</p>';
        }
        ?>
    </section>

    <script src="../admin.js"></script>
</body>

</html>