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

if (isset($_POST['save_list'])) {
    if ($user_id != '') {
        $list_id = $_POST['list_id'];
        $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

        $verify_list = $conn->prepare("select * from `bookmark` where user_id = ? and danhsach_id = ?");
        $verify_list->execute([$user_id, $list_id]);
        if ($verify_list->rowCount() > 0) {
            $remove_list = $conn->prepare("delete from `bookmark` where user_id = ? and danhsach_id = ?");
            $remove_list->execute([$user_id, $list_id]);
            $message[] = 'Khóa học đã xóa';
        } else {
            $add_list = $conn->prepare("insert into `bookmark`(user_id,danhsach_id) values(?,?)");
            $add_list->execute([$user_id, $list_id]);
            $message[] = 'Khóa học đã được lưu';
        }
    } else {
        $message[] = 'Đăng nhập cái đã!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Xem khóa học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>
    <section class="danhsach-details">
        <h1 class="heading">Chi tiết khóa học</h1>
        <div class="row">
            <?php
            $select_danhsach = $conn->prepare("select * from `danhsach` where id = ? and status= ? limit 1");
            $select_danhsach->execute([$get_id, 'Hoạt động']);
            if ($select_danhsach->rowCount() > 0) {
                while ($fetch_danhsach = $select_danhsach->fetch(PDO::FETCH_ASSOC)) {
                    $danhsach_id = $fetch_danhsach['id'];
                    $count_content = $conn->prepare("select * from `content` where danhsach_id = ? and status = ?");
                    $count_content->execute([$danhsach_id, 'Hoạt động']);
                    $total_content = $count_content->rowCount();
                    $select_giasu = $conn->prepare("select * from `giasu` where id = ? limit 1");
                    $select_giasu->execute([$fetch_danhsach['giasu_id']]);
                    $fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC);
                    $select_bookmark = $conn->prepare("select * from `bookmark` where user_id= ? and danhsach_id =?");
                    $select_bookmark->execute([$user_id, $danhsach_id]);
            ?>
                    <div class="col">
                        <form action="" method="post">
                            <input type="hidden" name="list_id" value="<?= $danhsach_id; ?>">
                            <?php
                            if ($select_bookmark->rowCount() > 0) { ?>
                                <button type="submit" name="save_list" class="inline-bt"><i class="fas fa-bookmark"></i><span>Đã lưu</span></button>
                            <?php
                            } else { ?>
                                <button type="submit" name="save_list" class="inline-option-bt"><i class="far fa-bookmark"></i><span>Lưu khóa học</span></button>
                            <?php
                            } ?>
                        </form>
                        <div class="thumb">
                            <span><?= $total_content; ?></span>
                            <img src="upload/<?= $fetch_danhsach['thumbnail']; ?>" alt="">
                        </div>
                    </div>
                    <div class="col">
                        <div class="giasu">
                            <img src="upload/<?= $fetch_giasu['image']; ?>" alt="">
                            <div>
                                <h3><?= $fetch_giasu['name']; ?></h3>
                                <span><?= $fetch_giasu['job']; ?></span>
                            </div>
                        </div>
                        <h3 class="title"><?= $fetch_danhsach['title']; ?></h3>
                        <p class="desc"><?= $fetch_danhsach['desc']; ?></p>
                        <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_danhsach['date']; ?></span></div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Không tìm thấy khóa học!</p>';
            }
            ?>
        </div>
    </section>

    <section class="danhsach-videos">
        <h1 class="heading">video khóa học </h1>
        <div class="box-container">
            <?php
            $select_content = $conn->prepare("select * from `content` where danhsach_id = ? and status = ? order by date desc");
            $select_content->execute([$get_id, 'Hoạt động']);
            if ($select_content->rowCount() > 0) {
                while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                    // $content_id = $fetch_content['id'];
                    // $select_video = $conn->prepare("select * from `video` where content_id =? limit 1");
                    // $select_video->execute([$content_id]);
                    // $fetch_video = $select_video->fetch(PDO::FETCH_ASSOC);

            ?>
                    <a href="video.php?get_id=<?= $fetch_content['id']; ?>" class="box">
                        <i class="fas fa-play"></i>
                        <img src="upload/<?= $fetch_content['thumbnail']; ?>" alt="">
                        <h3><?= $fetch_content['title']; ?></h3>
                    </a>
            <?php
                }
            } else {
                echo '<p class="empty">Chưa có khóa học nào được thêm vào!</p>';
            }
            ?>
        </div>
    </section>


    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>