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
    <title>Yolostudy: Tìm khóa học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>
    <section class="course">
        <h1 class="heading">Kết quả tìm kiếm</h1>
        <div class="box-container">
            <?php
            if (isset($_POST['search_box']) or isset($_POST['search_bt'])) {
                $search_box = $_POST['search_box'];
                $select_courses = $conn->prepare("select * from `danhsach` where title like '%{$search_box}%' and status = ? order by date desc");
                $select_courses->execute(['Hoạt động']);
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
                                <img src="upload/<?= $fetch_giasu['image']; ?>" alt="">
                                <div>
                                    <h3><?= $fetch_giasu['name']; ?></h3>
                                    <span><?= $fetch_course['date']; ?></span>
                                </div>
                            </div>
                            <div class="thumb">
                                <span><?= $total_courses; ?></span>
                                <img src="upload/<?= $fetch_course['thumbnail']; ?>" alt="">
                            </div>
                            <h3 class="title"><?= $fetch_course['title']; ?></h3>
                            <a href="danhsach.php?get_id=<?= $course_id; ?>" class="inline-bt">Xem khóa học</a>
                        </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">Không tìm thấy khóa học mà bạn cần tìm!</p>';
                }
            }else {
                echo '<p class="empty">Nhập gì đó đi 3!</p>';
            }
            ?>

        </div>
    </section>


    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>