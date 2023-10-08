<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_POST['delete_content'])) {
    $delete_id = $_POST['content_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
    $verify_content = $conn->prepare("select * from `content` where id = ?");
    $verify_content->execute([$delete_id]);
    if ($verify_content->rowCount() > 0) {
        $fetch_content = $verify_content->fetch(PDO::FETCH_ASSOC);
        unlink('../upload/' . $fetch_content['thumbnail']);
        unlink('../upload/' . $fetch_content['video']);
        $delete_comment = $conn->prepare("delete from `comments` where content_id = ?");
        $delete_comment->execute([$delete_id]);
        $delete_likes = $conn->prepare("delete from `likes` where content_id = ?");
        $delete_likes->execute([$delete_id]);
        $delete_content = $conn->prepare("delete from `content` where id = ?");
        $delete_content->execute([$delete_id]);
        $message[] = 'Xoá bài học thành công';
    } else {
        $message[] = 'Bài học đã được xóa';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tất cả nội dung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>

    <section class="contents">
        <h1 class="heading">Tất cả bài học</h1>
        <div class="box-container">
            <?php
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE giasu_id = ?");
            $select_content->execute([$giasu_id]);

            if ($select_content->rowCount() > 0) {
                while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
                <div class="flex">
                    <p><i class="fas fa-circle-dot"
                            style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"></i><span
                            style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"><?= $fetch_content['status']; ?></span>
                    </p>
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