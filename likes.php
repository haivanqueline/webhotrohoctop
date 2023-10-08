<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_like = $conn->prepare("select * from `likes` where user_id = ? and content_id = ?");
    $verify_like->execute([$user_id, $delete_id]);

    if ($verify_like->rowCount() > 0) {
        $delete_like = $conn->prepare("delete from `likes` where user_id = ? and content_id = ?");
        $delete_like->execute([$user_id, $delete_id]);
        $message[] = "Đã xóa thích!";
    } else {
        $message[] = 'Đã được xóa!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yolostudy: Thích</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="course">
        <h1 class="heading">Tất cả bài học đã thích</h1>
        <div class="box-container">
            <?php
            $select_likes = $conn->prepare("select * from `likes` where user_id = ?");
            $select_likes->execute([$user_id]);
            if ($select_likes->rowCount() > 0) {
                while ($fetch_likes = $select_likes->fetch(PDO::FETCH_ASSOC)) {
                    $select_courses = $conn->prepare("select * from `content` where id = ? and status = ? order by date desc");
                    $select_courses->execute([$fetch_likes['content_id'], 'Hoạt động']);
                    if ($select_courses->rowCount() > 0) {
                        while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                            $course_id = $fetch_course['id'];

                            $select_giasu = $conn->prepare("select * from `giasu` where id = ?");
                            $select_giasu->execute([$fetch_course['giasu_id']]);
                            $fetch_giasu = $select_giasu->fetch(PDO::FETCH_ASSOC);
            ?>
                            <div class="box">
                                <div class="giasu">
                                    <img src="upload/<?= $fetch_giasu['image']; ?>" alt="">
                                    <div>
                                        <h3><?= $fetch_giasu['name']; ?></h3>
                                        <span><?= $fetch_course['date']; ?></span>
                                    </div>
                                </div>
                                <div class="thumb">
                                    <img src="upload/<?= $fetch_course['thumbnail']; ?>" alt="">
                                </div>
                                <h3 class="title"><?= $fetch_course['title']; ?></h3>
                                <form action="" method="post" class="flex-bt">
                                    <input type="hidden" name="delete_id" value="<?= $fetch_likes['content_id']; ?>">
                                    <a href="video.php?get_id=<?= $course_id; ?>" class="inline-bt">Xem bài học</a>
                                    <input type="submit" value="Gỡ thích" class="inline-delete-bt" name="delete">
                                </form>
                            </div>
            <?php
                        }
                    } else {
                        echo '<p class="empty">Không có khóa học nào hết</p>';
                    }
                }
            } else {
                echo '<p class="empty">Không có gì hết, thích gì đó đi 3!</p>';
            }
            ?>

        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js"></script>
</body>

</html>