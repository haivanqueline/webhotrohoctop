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
        $message[]='Xoá bài học thành công';
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
    <title>Xem danh sách</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>

    <section class="danhsach-details">
        <h1 class="heading">Chi tiết danh sách</h1>
        <?php
        $select_danhsach = $conn->prepare("select * from `danhsach` where id = ? limit 1");
        $select_danhsach->execute([$get_id]);
        if ($select_danhsach->rowCount() > 0) {
            while ($fetch_danhsach = $select_danhsach->fetch(PDO::FETCH_ASSOC)) {
                $count_content = $conn->prepare("select * from `content` where danhsach_id = ?");
                $count_content->execute([$get_id]);
                $total_contents = $count_content->rowCount();
        ?>
                <div class="row">
                    <div class="thumb">
                        <img src="../upload/<?= $fetch_danhsach['thumbnail']; ?>" alt="">
                        <div class="flex">
                            <p><i class="fas fa-video"></i><span><?= $total_contents; ?></span></p>
                            <p><i class="fas fa-calendar"></i><span><?= $fetch_danhsach['date']; ?></span></p>
                        </div>
                    </div>
                    <div class="details">
                        <h3 class="title"><?= $fetch_danhsach['title']; ?></h3>
                        <p class="desc"><?= $fetch_danhsach['desc']; ?></p>
                        <form action="" method="POST" class="flex-bt">
                            <input type="hidden" name="delete_id" value="<?= $fetch_danhsach['id']; ?>">
                            <a href="updatedanhsach.php?get_id=<?= $fetch_danhsach['id']; ?>" class="option-bt">Cập nhập</a>
                            <input type="submit" value="Xoá" name="delete_danhsach" class="delete-bt">
                        </form>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Không có danh sách nào hết!</p>';
        }
        ?>
    </section>

    <section class="contents">
        <h1 class="heading">Nội dung danh sách</h1>
        <div class="box-container">
            <?php
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE danhsach_id = ? AND giasu_id = ?");
            $select_content->execute([$get_id, $giasu_id]);

            if ($select_content->rowCount() > 0) {
                while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <div class="flex">
                            <p><i class="fas fa-circle-dot" style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"></i><span style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"><?= $fetch_content['status']; ?></span></p>
                            <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
                        </div>
                        <img src="../upload/<?= $fetch_content['thumbnail']; ?>" alt="">
                        <h3 class="title"><?= $fetch_content['title']; ?></h3>
                        <a href="viewcontent.php?get_id=<?= $fetch_content['id']; ?>" class="bt">Xem bài học</a>
                        <form action="" class="flex-bt" method="POST">
                            <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                            <a href="updatecontent.php?get_id=<?= $fetch_content['id']; ?>" class="option-bt">Cập nhập</a>
                            <input type="submit" value="Xoá" name="delete_content" class="delete-bt">
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Không có bài học nào hết!<a href="addcontent.php" style="margin-top: 1.5rem;" class="bt">Thêm bài học mới</a></p>';
            }
            ?>
        </div>
    </section>

    <script src="../admin.js"></script>
</body>

</html>