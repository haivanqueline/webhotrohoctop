<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}


$count_bookmark = $conn->prepare("select * from `bookmark` where user_id = ?");
$count_bookmark->execute([$user_id]);
$total_bookmark = $count_bookmark->rowCount();

$count_likes = $conn->prepare("select * from `likes` where user_id = ?");
$count_likes->execute([$user_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("select * from `comments` where user_id = ?");
$count_comments->execute([$user_id]);
$total_comments = $count_comments->rowCount();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Hồ sơ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>
    
    <section class="profile">
        <h1 class="heading">Hồ sơ chi tiết</h1>
        <div class="details">
            <div class="giasu">
                <img src="upload/<?= $fetch_profile['image'] ?>" alt="">
                <h3><?= $fetch_profile['name'] ?></h3>
                <p><?= $fetch_profile['email'] ?></p>
                <span>Học sinh</span>
                <a href="update.php" class="inline-bt">Cập nhập hồ sơ</a>
            </div>
            <div class="box-container">
                <div class="box">
                    <h3><?= $total_bookmark ?></h3>
                    <p>Danh sách đã lưu</p>
                    <a href="danhsach.php" class="bt">Xem danh sách</a>
                </div>
                <div class="box">
                    <h3><?= $total_likes ?></h3>
                    <p>Tổng lượt thích</p>
                    <a href="khoahoc.php" class="bt">Xem nội dung</a>
                </div>
                <div class="box">
                    <h3><?= $total_comments ?></h3>
                    <p>Tổng lượt bình luận</p>
                    <a href="comments.php" class="bt">Xem bình luận</a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>