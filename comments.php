<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

if (isset($_POST['delete_comment'])) {
    $delete_id = $_POST['comment_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("select * from `comments` where id = ?");
    $verify_comment->execute([$delete_id]);

    if ($verify_comment->rowCount() > 0) {
        $delete_comment = $conn->prepare("delete from `comments` where id =?");
        $delete_comment->execute([$delete_id]);
        $message[] = 'Bình luận đã xóa thành công!';
    } else {
        $message[] = 'Bình luận đã được xóa!';
    }
}
if (isset($_POST['edit_comment'])) {
    $edit_id = $_POST['edit_id'];
    $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);

    $comment_box = $_POST['comment_box'];
    $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);

    $verify_edit_comment = $conn->prepare("select * from `comments` where id = ? and comment = ?");
    $verify_edit_comment->execute([$edit_id, $comment_box]);

    if ($verify_edit_comment->rowCount() > 0) {
        $message[] = 'Bình luận đã được sửa!';
    } else {
        $edit_comment = $conn->prepare("update `comments` set comment =? where id =?");
        $edit_comment->execute([$comment_box, $edit_id]);
        $message[] = 'Bình luận đã được sửa thành công!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Bình luận</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <?php
    if (isset($_POST['update_comment'])) {
        $update_id = $_POST['comment_id'];
        $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
        $select_update_comment = $conn->prepare("select * from `comments` where id = ? limit 1");
        $select_update_comment->execute([$update_id]);
        $fetch_update_comment = $select_update_comment->fetch(PDO::FETCH_ASSOC);
    ?>
        <section class="comment-form">
            <h1 class="heading">Cập nhập bình luận</h1>
            <form action="" method="post">
                <input type="hidden" name="edit_id" value="<?= $fetch_update_comment['id']; ?>">
                <textarea name="comment_box" class="box" required maxlength="1000" placeholder="Nhập bình luận" cols="30" rows="10"><?= $fetch_update_comment['comment']; ?></textarea>
                <div class="flex-bt">
                    <a href="comments.php" class="inline-option-bt">Hủy chỉnh sửa</a>
                    <input type="submit" value="Chỉnh sửa bình luận" name="edit_comment" class="inline-bt">
                </div>
            </form>
        </section>
    <?php
    }
    ?>



    <section class="comments">
        <h1 class="heading">Bình luận của bạn</h1>
        <div class="box-container">
            <?php
            $select_comments = $conn->prepare("select * from `comments` where user_id = ?");
            $select_comments->execute([$user_id]);
            if ($select_comments->rowCount() > 0) {
                while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                    $comment_id = $fetch_comment['id'];

                    $select_content = $conn->prepare("select * from `content` where id = ?");
                    $select_content->execute([$fetch_comment['content_id']]);
                    $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
            ?>
                    <div class="box">
                        <div class="comment-content">
                            <p><?= $fetch_content['title']; ?></p>
                            <a href="video.php?get_id=<?= $fetch_content['id']; ?>">Xem bài học</a>
                        </div>
                        <p class="comment-box"><?= $fetch_comment['comment']; ?></p>
                            <form action="" method="post">
                                <input type="hidden" name="comment_id" value="<?= $comment_id; ?>">
                                <input type="submit" value="Cập nhập bình luận" name="update_comment" class="inline-option-bt">
                                <input type="submit" value="Xóa bình luận" name="delete_comment" class="inline-delete-bt" onclick="return confirm('Bạn có muốn xóa bình luận này không?')">
                            </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty" style="padding: 0;">Không có bình luận nào hết</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>