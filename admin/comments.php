<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_POST['delete_comment'])) {
    $delete_id = $_POST['comment_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
    $verify_comment->execute([$delete_id]);

    if ($verify_comment->rowCount() > 0) {
        $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
        $delete_comment->execute([$delete_id]);
        $message[] = 'Bình luận đã xóa thành công!';
    } else {
        $message[] = 'Bình luận đã được xóa!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bình luận</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="comments">
        <h1 class="heading">Bình luận của người dùng</h1>
        <div class="box-container">
            <?php
            $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE giasu_id = ?");
            $select_comments->execute([$giasu_id]);
            if ($select_comments->rowCount() > 0) {
                while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                    $comment_id = $fetch_comment['id'];
                    $select_commentor = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
                    $select_commentor->execute([$fetch_comment['user_id']]);
                    if ($fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC)) {
                        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
                        $select_content->execute([$fetch_comment['content_id']]);
                        if ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
            ?>
                            <div class="box">
                                <div class="comment-content">
                                    <p><?= $fetch_content['title']; ?></p>
                                    <a href="viewcontent.php?get_id=<?= $fetch_content['id']; ?>">Xem bài học</a>
                                </div>
                                <div class="user">
                                    <img src="../upload/<?= $fetch_commentor['image']; ?>" alt="">
                                    <div>
                                        <h3><?= $fetch_commentor['name']; ?></h3>
                                        <span><?= $fetch_comment['date']; ?></span>
                                    </div>
                                </div>
                                <p class="comment-box"><?= $fetch_comment['comment']; ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                                    <input type="submit" value="Xóa bình luận" name="delete_comment" class="inline-delete-bt" onclick="return confirm('Bạn có muốn xóa bình luận này không?')">
                                </form>
                            </div>
            <?php
                        }
                    }
                }
            } else {
                echo '<p class="empty">Không có bình luận nào hết</p>';
            }
            ?>
        </div>
    </section>
    <script src="../admin.js"></script>
</body>

</html>