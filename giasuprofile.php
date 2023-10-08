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
    header('location:giasu.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Hồ sơ gia sư</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="giasu-profile">
        <h1 class="heading">Hồ sơ gia sư</h1>
        <?php
        $select_giasu = $conn->prepare("select * from `giasu` where email=? limit 1");
        $select_giasu->execute([$get_id]);
        if ($select_giasu->rowCount() > 0) {
            while ($fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC)) {
                $giasu_id = $fetch_giasu['id'];

                $count_likes = $conn->prepare("select * from `likes` where giasu_id = ?");
                $count_likes->execute([$giasu_id]);
                $total_likes = $count_likes->rowCount();

                $count_comments = $conn->prepare("select * from `comments` where giasu_id = ?");
                $count_comments->execute([$giasu_id]);
                $total_comments = $count_comments->rowCount();

                $count_content = $conn->prepare("select * from `content` where giasu_id = ? ");
                $count_content->execute([$giasu_id]);
                $total_content = $count_content->rowCount();

                $count_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ?");
                $count_danhsach->execute([$giasu_id]);
                $total_danhsach = $count_danhsach->rowCount();
        ?>
                <div class="details">
                    <div class="giasu">
                        <img src="upload/<?= $fetch_giasu['image'];?>" alt="">
                        <h3 class="name"><?= $fetch_giasu['name'];?></h3>
                        <span class="job"><?= $fetch_giasu['job'];?></span>
                        <p class="email"><?= $fetch_giasu['email'];?></p>
                    </div>
                    <div class="box-container">
                        <p>Tổng danh sách: <span><?= $total_danhsach;?></span></p>
                        <p>Tổng video: <span><?= $total_content;?></span></p>
                        <p>Tổng thích: <span><?= $total_likes;?></span></p>
                        <p>Tổng bình luận: <span><?= $total_comments;?></span></p>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Không có gia sư nào ở đây hết!</p>';
        }
        ?>
    </section>

    <section class="course">
        <h1 class="heading">Khóa học của gia sư</h1>
        <div class="box-container">
            <?php
            $select_email = $conn->prepare("select * from `giasu` where email=? limit 1");
            $select_email->execute([$get_id]);
            $fetch_giasu_id = $select_email->fetch(PDO::FETCH_ASSOC);
            $select_courses = $conn->prepare("select * from `danhsach` where giasu_id = ? and status = ? order by date desc");
            $select_courses->execute([$fetch_giasu_id['id'],'Hoạt động']);
            if ($select_courses->rowCount() > 0) {
                while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                    $course_id = $fetch_course['id'];
                    $count_course = $conn->prepare("select * from `content` where danhsach_id = ?");
                    $count_course->execute([$course_id]);
                    $total_courses = $count_course->rowCount();

                    $select_giasu = $conn->prepare("select * from `giasu` where id = ?");
                    $select_giasu->execute([$fetch_course['giasu_id']]);
                    $fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box">
                <div class="giasu">
                    <img src="upload/<?= $fetch_giasu['image'];?>" alt="">
                    <div>
                        <h3><?= $fetch_giasu['name'];?></h3>
                        <span><?= $fetch_course['date'];?></span>
                    </div>
                </div>
                <div class="thumb">
                    <span><?= $total_courses;?></span>
                    <img src="upload/<?= $fetch_course['thumbnail'];?>" alt="">
                </div>
                <h3 class="title"><?= $fetch_course['title'];?></h3>
                <a href="danhsach.php?get_id=<?= $course_id;?>" class="inline-bt">Xem khóa học</a>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Không có khóa học nào hết</p>';
            }
            ?>

        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>