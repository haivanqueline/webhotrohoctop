<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

$count_likes = $conn->prepare("select * from `likes` where user_id = ?");
$count_likes->execute([$user_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("select * from `comments` where user_id = ?");
$count_comments->execute([$user_id]);
$total_comments = $count_comments->rowCount();

$count_bookmark = $conn->prepare("select * from `bookmark` where user_id = ?");
$count_bookmark->execute([$user_id]);
$total_bookmark = $count_bookmark->rowCount();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>
    <section class="quick-select">
        <h1 class="heading">Tùy chọn nhanh</h1>
        <div class="box-container">
            <?php
            if ($user_id != '') {
            ?>
            <div class="box">
                <h3 class="title">Lượt thích và bình luận</h3>
                <p>Tổng lượt thích: <span><?= $total_likes; ?></span></p>
                <a href="likes.php" class="inline-bt">Xem lượt thích</a>
                <p>Tổng lượt bình luận: <span><?= $total_comments; ?></span></p>
                <a href="comments.php" class="inline-bt">Xem lượt bình luận</a>
                <p>Danh sách đã lưu: <span><?= $total_bookmark; ?></span></p>
                <a href="bookmark.php" class="inline-bt">Xem danh sách</a>
            </div>
            <?php
            } else {
            ?>
            <div class="box" style="text-align: center;">
                <h3 class="title">Đăng nhập hoặc đăng ký</h3>
                <div class="flex-bt">
                    <a href="dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="dangky.php" class="option-bt">Đăng ký</a>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="box">
                <h3 class="title">Top danh mục</h3>
                <div class="flex">
                    <a href="#"><i class="fas fa-code"></i><span>Lập trình</span></a>
                    <a href="#"><i class="fas fa-chart-simple"></i><span>Kinh doanh</span></a>
                    <a href="#"><i class="fas fa-pen"></i><span>Thiết kế</span></a>
                    <a href="#"><i class="fas fa-chart-line"></i><span>Marketing</span></a>
                    <a href="#"><i class="fas fa-music"></i><span>Âm nhạc</span></a>
                    <a href="#"><i class="fas fa-camera"></i><span>Nhiếp ảnh gia</span></a>
                    <a href="#"><i class="fas fa-cog"></i><span>Phần cứng</span></a>
                    <a href="#"><i class="fas fa-vial"></i><span>Khoa học</span></a>
                    <a href="#"><i class="fa-regular fa-hard-drive"></i><span>Toán học</span></a>
                </div>
            </div>
            <div class="box">
                <h3 class="title">Chủ đề nổi bật</h3>
                <div class="flex">
                    <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
                    <a href="#"><i class="fa-regular fa-hard-drive"></i><span>Toán học</span></a>
                    <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
                    <a href="#"><i class="fa-brands fa-python"></i><span>Python</span></a>
                    <a href="#"><i class="fa-brands fa-node"></i><span>Node JS</span></a>
                    <a href="#"><i class="fa-brands fa-vuejs"></i><span>Vue JS</span></a>
                    <a href="#"><i class="fab fa-php"></i></i><span>PHP</span></a>
                    <a href="#"><i class="fa-brands fa-react"></i></i><span>React JS</span></a>
                </div>
            </div>

            <div class="box giasu">
                <h3 class="title">Trở thành gia sư</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia, ullam!</p>
                <a href="admin/dangky.php" class="inline-bt">Bắt đầu nào!</a>
            </div>
        </div>
    </section>

    <section class="course">
        <h1 class="heading">Khóa học mới nhất</h1>
        <div class="box-container">
            <?php
            $select_courses = $conn->prepare("select * from `danhsach` where status = ? order by date desc limit 6");
            $select_courses->execute(['Hoạt động']);
            if ($select_courses->rowCount() > 0) {
                while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                    $course_id = $fetch_course['id'];
                    $count_course = $conn->prepare("select * from `content` where danhsach_id = ? and status = ?");
                    $count_course->execute([$course_id, 'Hoạt động']);
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
        <div style="margin-top: 2rem; text-align: center;">
            <a href="khoahoc.php" class="inline-option-bt">Xem tất cả</a>
        </div>
    </section>




    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>