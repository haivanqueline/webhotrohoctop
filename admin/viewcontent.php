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
        header('location:contents.php');
    } else {
        $message[] = 'Bài học đã được xóa';
    }
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem nội dung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>

    <section class="view-content">
        <?php
        $select_content = $conn->prepare("select * from `content` where id=?");
        $select_content->execute([$get_id]);
        if ($select_content->rowCount() > 0) {
            while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                $content_id = $fetch_content['id'];

                $count_likes = $conn->prepare("select * from `likes` where giasu_id = ? and content_id = ?");
                $count_likes->execute([$giasu_id, $content_id]);
                $total_likes = $count_likes->rowCount();

                $count_comments = $conn->prepare("select * from `comments` where giasu_id = ? and content_id = ?");
                $count_comments->execute([$giasu_id, $content_id]);
                $total_comments = $count_comments->rowCount();
        ?>
                <div class="content">
                    <video src="../upload/<?= $fetch_content['video']; ?>" poster="../upload/<?= $fetch_content['thumbnail']; ?>" controls autoplay></video>
                    <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></div>
                    <h3 class="title"><?= $fetch_content['title']; ?></h3>
                    <div class="flex">
                        <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
                        <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
                    </div>
                    <p class="desc"><?= $fetch_content['desc']; ?></p>
                    <form action="" method="post" class="flex-bt">
                        <input type="hidden" name="content_id" value="<?= $content_id; ?>">
                        <input type="submit" value="Xóa bài học" name="delete_content" class="delete-bt">
                        <a href="updatecontent.php?get_id=<?= $content_id; ?>" class="option-bt">Cập nhập bài học</a>
                    </form>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Không tìm thấy bài học nào hết!</p>';
        }
        ?>
    </section>

    <section class="comments">
        <h1 class="heading">Bình luận</h1>
        <div class="box-container">
            <?php
            $select_comments = $conn->prepare("select * from `comments` where content_id = ? and giasu_id = ?");
            $select_comments->execute([$get_id, $giasu_id]);
            if ($select_comments->rowCount() > 0) {
                while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                    $comment_id = $fetch_comment['id'];
                    $select_commentor = $conn->prepare("select * from `user` where id = ?");
                    $select_commentor->execute([$fetch_comment['user_id']]);
                    if ($fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC)) {
            ?>
                        <div class="box">
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
                                <input type="submit" value="Xóa bình luận" name="delete_comment" class="inline-delete-bt">
                            </form>
                        </div>
            <?php
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