<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:khoahoc.php');
}
if (isset($_POST['like_content'])) {
    if ($user_id != '') {
        $like_id = $_POST['content_id'];
        $like_id = filter_var($like_id, FILTER_SANITIZE_STRING);

        $get_content = $conn->prepare("select * from `content` where id=? limit 1");
        $get_content->execute([$like_id]);
        $fetch_get_content = $get_content->fetch(PDO::FETCH_ASSOC);
        $giasu_id = $fetch_get_content['giasu_id'];

        $verify_like = $conn->prepare("select * from `likes` where user_id = ? and content_id =?");
        $verify_like->execute([$user_id, $like_id]);

        if ($verify_like->rowCount() > 0) {
            $delete_like = $conn->prepare("delete from `likes` where user_id =? and content_id =?");
            $delete_like->execute([$user_id, $like_id]);
            $message[] = "Đã xóa thích!";
        } else {
            $insert_like = $conn->prepare("insert into `likes` (user_id,giasu_id,content_id) values (?,?,?)");
            $insert_like->execute([$user_id, $giasu_id, $like_id]);
            $message[] = 'Đã thêm vào thích!';
        }
    } else {
        $message[] = 'Đăng nhập cái đã!';
    }
}
if (isset($_POST['add_comment'])) {
    $id = create_unique_id();

    $comment_box = $_POST['comment_box'];
    $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);

    $select_content_giasu = $conn->prepare("select * from `content` where id = ?");
    $select_content_giasu->execute([$get_id]);
    $fetch_content_giasu_id = $select_content_giasu->fetch(PDO::FETCH_ASSOC);
    $content_giasu_id = $fetch_content_giasu_id['giasu_id'];

    $verify_comment = $conn->prepare("select * from `comments` where content_id = ? and user_id = ? and giasu_id = ? and comment = ?");
    $verify_comment->execute([$get_id, $user_id, $content_giasu_id, $comment_box]);

    if ($verify_comment->rowCount() > 0) {
        $message[] = 'Bình luận đã được thêm!';
    } else {
        $insert_comment = $conn->prepare("insert into `comments` (id,content_id,user_id,giasu_id,comment) values (?,?,?,?,?)");
        $insert_comment->execute([$id, $get_id, $user_id, $content_giasu_id, $comment_box]);
        $message[] = 'Đã thêm vào bình luận!';
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
    <title>Yolostudy: Viđéồ</title>
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
                    <a href="video.php?get_id=<?= $get_id; ?>" class="inline-option-bt">Hủy chỉnh sửa</a>
                    <input type="submit" value="Chỉnh sửa bình luận" name="edit_comment" class="inline-bt">
                </div>
            </form>
        </section>
    <?php
    }
    ?>
    <section class="watch-video">
        <?php
        $select_content = $conn->prepare("select * from `content` where id = ? and status = ?");
        $select_content->execute([$get_id, 'Hoạt động']);
        if ($select_content->rowCount() > 0) {
            while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                $content_id = $fetch_content['id'];
                $select_likes = $conn->prepare("select * from `likes` where content_id =?");
                $select_likes->execute([$content_id]);
                $total_likes = $select_likes->rowCount();
                $user_likes =  $conn->prepare("select * from `likes` where user_id = ? and content_id =?");
                $user_likes->execute([$user_id, $content_id]);
                $select_giasu = $conn->prepare("select * from `giasu` where id =?");
                $select_giasu->execute([$fetch_content['giasu_id']]);
                $fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC);
        ?>
                <div class="content">
                    <video src="upload/<?= $fetch_content['video']; ?>" controls autoplay poster="upload/<?= $fetch_content['thumbnail']; ?>" class="video"></video>
                    <h3 class="title"><?= $fetch_content['title']; ?></h3>
                    <div class="info">
                        <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
                        <p><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></p>
                    </div>
                    <div class="giasu">
                        <img src="upload/<?= $fetch_giasu['image']; ?>" alt="">
                        <div>
                            <h3><?= $fetch_giasu['name']; ?></h3>
                            <span><?= $fetch_giasu['job']; ?></span>
                        </div>
                    </div>
                    <form action="" method="post" class="flex">
                        <input type="hidden" name="content_id" value="<?= $content_id; ?>">
                        <a href="danhsach.php?get_id=<?= $fetch_content['danhsach_id']; ?>" class="inline-bt">Xem danh sách</a>
                        <?php
                        if ($user_likes->rowCount() > 0) { ?>
                            <button class="inline-bt" type="submit" name="like_content"><i class="fas fa-heart"></i><span>Đã thích</span></button>
                        <?php
                        } else {
                        ?>
                            <button class="inline-option-bt" type="submit" name="like_content"><i class="far fa-heart"></i><span>Thích</span></button>
                        <?php } ?>
                    </form>
                    <p class="desc"><?= $fetch_content['desc']; ?></p>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Không có video nào ở đây hết!</p>';
        }
        ?>
    </section>

    <section class="comment-form">
        <h1 class="heading">Cập nhập bình luận</h1>
        <form action="" method="post">
            <textarea name="comment_box" class="box" required maxlength="1000" placeholder="Nhập bình luận" cols="30" rows="10"></textarea>
            <input type="submit" value="Đăng bình luận" name="add_comment" class="inline-bt">
        </form>
    </section>


    <section class="comments">
        <h1 class="heading">Bình luận</h1>
        <div class="box-container">
            <?php
            $select_comments = $conn->prepare("select * from `comments` where content_id = ?");
            $select_comments->execute([$get_id]);
            if ($select_comments->rowCount() > 0) {
                while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                    $comment_id = $fetch_comment['id'];
                    $select_commentor = $conn->prepare("select * from `user` where id = ?");
                    $select_commentor->execute([$fetch_comment['user_id']]);
                    $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
            ?>
                    <div class="box" <?php if ($fetch_comment['user_id'] == $user_id) {
                                            echo 'style="order:-1;"';
                                        } ?>>
                        <div class="user">
                            <img src="upload/<?= $fetch_commentor['image']; ?>" alt="">
                            <div>
                                <h3><?= $fetch_commentor['name']; ?></h3>
                                <span><?= $fetch_comment['date']; ?></span>
                            </div>
                        </div>
                        <p class="comment-box"><?= $fetch_comment['comment']; ?></p>
                        <?php if ($fetch_comment['user_id'] == $user_id) { ?>
                            <form action="" method="post">
                                <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                                <input type="submit" value="Cập nhập bình luận" name="update_comment" class="inline-option-bt">
                                <input type="submit" value="Xóa bình luận" name="delete_comment" class="inline-delete-bt" onclick="return confirm('Bạn có muốn xóa bình luận này không?')">
                            </form>
                        <?php } ?>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Không có bình luận nào hết</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>