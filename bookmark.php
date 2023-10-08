<?php
include 'tp/connect.php';
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['delete'])) {
    if ($user_id != '') {
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_list = $conn->prepare("select * from `bookmark` where user_id = ? and danhsach_id = ?");
        $verify_list->execute([$user_id, $delete_id]);
        if ($verify_list->rowCount() > 0) {
            $remove_list = $conn->prepare("delete from `bookmark` where user_id = ? and danhsach_id = ?");
            $remove_list->execute([$user_id, $delete_id]);
            $message[] = 'Danh sách đã xóa';
        } else {
            $message[] = 'Danh sách đã được xóa';
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
    <title>Yolostudy: Danh sách đã lưu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="course">
        <h1 class="heading">Danh sách đã lưu</h1>
        <div class="box-container">
            <?php
            $select_bookmark = $conn->prepare("select * from `bookmark` where user_id = ?");
            $select_bookmark->execute([$user_id]);
            if ($select_bookmark->rowCount() > 0) {
                while ($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)) {
                    $select_courses = $conn->prepare("select * from `danhsach` where id = ? and status = ? order by date desc");
                    $select_courses->execute([$fetch_bookmark['danhsach_id'], 'Hoạt động']);
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
                                <form action="" method="post" class="flex-bt">
                                    <input type="hidden" name="delete_id" value="<?= $course_id; ?>">
                                    <a href="danhsach.php?get_id=<?= $course_id; ?>" class="inline-bt">Xem khóa học</a>
                                    <input type="submit" value="Xóa lưu" name="delete" class="inline-delete-bt" onclick="return confirm('Bạn chắc chắn muốn xóa không!');">
                                </form>
                            </div>
            <?php
                        }
                    } else {
                        echo '<p class="empty">Không có khóa học nào hết</p>';
                    }
                }
            } else {
                echo '<p class="empty">Không có khóa học nào được lưu hết</p>';
            }
            ?>

        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>