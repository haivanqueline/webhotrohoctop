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
    <title>Quản trị viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>



    <section class="dashboard">
        <h1 class="heading">Quản trị</h1>
        <div class="box-container">
            <div class="box">
                <h3>Chào!!((:</h3>
                <p><?=$fetch_profile['name']; ?></p>
                <a href="profile.php" class="bt">Xem hồ sơ</a>
            </div>
            <div class="box">
                <h3><?=$total_contents; ?></h3>
                <p>Tổng bài học đã đăng</p>
                <a href="addcontent.php" class="bt">Thêm bài học mới!</a>
            </div>
            <div class="box">
                <h3><?=$total_danhsach; ?></h3>
                <p>Tổng danh sách đã đăng</p>
                <a href="adddanhsach.php" class="bt">Thêm danh sách mới!</a>
            </div>
            <div class="box">
                <h3><?=$total_likes; ?></h3>
                <p>Tổng lượt thích</p>
                <a href="contents.php" class="bt">Xem bài học</a>
            </div>
            <div class="box">
                <h3><?=$total_comments; ?></h3>
                <p>Tổng lượt bình luận</p>
                <a href="comments.php" class="bt">Xem bình luận</a>
            </div>
            <div class="box">
                <h3>Liên kết</h3>
                <p>Đăng nhập hoặc đăng ký</p>
                <div class="flex-bt">
                    <a href="dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="dangky.php" class="option-bt">Đăng ký</a>
                </div>
            </div>
        </div>
    </section>




    <script src="../admin.js"></script>
</body>

</html>