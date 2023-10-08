<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
$count_content = $conn->prepare("select * from `content` where giasu_id = ?");
$count_content->execute([$giasu_id]);
$total_contents = $count_content->rowCount();

$count_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ?");
$count_danhsach->execute([$giasu_id]);
$total_danhsach = $count_danhsach->rowCount();

$count_likes = $conn->prepare("select * from `likes` where giasu_id = ?");
$count_likes->execute([$giasu_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("select * from `comments` where giasu_id = ?");
$count_comments->execute([$giasu_id]);
$total_comments = $count_comments->rowCount();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="profile">
        <h1 class="heading">Hồ sơ chi tiết</h1>
        <div class="details">
            <div class="giasu">
                <img src="../upload/<?= $fetch_profile['image'] ?>" alt="">
                <h3><?= $fetch_profile['name'] ?></h3>
                <span><?= $fetch_profile['job'] ?></span>
                <a href="update.php" class="inline-bt">Cập nhập hồ sơ</a>
            </div>
            <div class="box-container">
                <div class="box">
                    <h3><?= $total_contents ?></h3>
                    <p>Tổng nội dung</p>
                    <a href="contents.php" class="bt">Xem nội dung</a>
                </div>
                <div class="box">
                    <h3><?= $total_danhsach ?></h3>
                    <p>Tổng danh sách</p>
                    <a href="danhsach.php" class="bt">Xem danh sách</a>
                </div>
                <div class="box">
                    <h3><?= $total_likes ?></h3>
                    <p>Tổng lượt thích</p>
                    <a href="contents.php" class="bt">Xem nội dung</a>
                </div>
                <div class="box">
                    <h3><?= $total_comments ?></h3>
                    <p>Tổng lượt bình luận</p>
                    <a href="comments.php" class="bt">Xem bình luận</a>
                </div>
            </div>
        </div>
    </section>
    <script src="../admin.js"></script>
</body>

</html>