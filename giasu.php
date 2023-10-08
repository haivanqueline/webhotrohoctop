<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Gia sư</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="teachers">
        <h1 class="heading">Gia sư chuyên nghiệp</h1>
        <form action="searchgiasu.php" method="post" class="giasu-search">
            <input type="text" name="search_giasu_box" placeholder="Tìm gia sư" maxlength="100" required>
            <button type="submit" name="search_giasu_bt" class="fas fa-search"></button>
        </form>
        <div class="box-container">
            <div class="box offer">
                <h3>Trở thành gia sư</h3>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Enim illo dignissimos temporibus aliquam libero laborum maxime ad placeat? Veniam, consectetur.</p>
                <a href="admin/dangky.php" class="inline-bt">Bắt đầu thôi</a>
            </div>
            <?php
            $select_giasu = $conn->prepare("select * from `giasu`");
            $select_giasu->execute();
            if ($select_giasu->rowCount() > 0) {
                while ($fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC)) {
                    $giasu_id = $fetch_giasu['id'];

                    $count_likes = $conn->prepare("select * from `likes` where giasu_id = ?");
                    $count_likes->execute([$giasu_id]);
                    $total_likes = $count_likes->rowCount();

                    $count_comments = $conn->prepare("select * from `comments` where giasu_id = ?");
                    $count_comments->execute([$giasu_id]);
                    $total_comments = $count_comments->rowCount();

                    $count_content = $conn->prepare("select * from `content` where giasu_id = ?");
                    $count_content->execute([$giasu_id]);
                    $total_content = $count_content->rowCount();

                    $count_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ?");
                    $count_danhsach->execute([$giasu_id]);
                    $total_danhsach = $count_danhsach->rowCount();
            ?>
                    <div class="box">
                        <div class="giasu">
                            <img src="upload/<?= $fetch_giasu['image']; ?>" alt="">
                            <div>
                                <h3><?= $fetch_giasu['name']; ?></h3>
                                <span><?= $fetch_giasu['job']; ?></span>
                            </div>
                        </div>
                        <p>Tổng video: <span><?= $total_content; ?></span></p>
                        <p>Tổng khóa học: <span><?= $total_danhsach; ?></span></p>
                        <p>Tổng thích: <span><?= $total_likes; ?></span></p>
                        <p>Tổng bình luận: <span><?= $total_comments; ?></span></p>
                        <a href="giasuprofile.php?get_id=<?= $fetch_giasu['email']; ?>" class="inline-bt">Xem hồ sơ</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Không có gia sư nào ở đây hết!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>