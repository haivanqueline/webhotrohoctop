<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm trang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="contents">
        <h1 class="heading">Bài học</h1>
        <div class="box-container">
            <?php
            if (isset($_POST['search_box']) or isset($_POST['search_bt'])) {
                $search_box = $_POST['search_box'];
                $select_content = $conn->prepare("SELECT * FROM `content` WHERE title LIKE '%{$search_box}%' and giasu_id = ? order by date desc");
                $select_content->execute([$giasu_id]);

                if ($select_content->rowCount() > 0) {
                    while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
            ?>
                        <div class="box">
                            <div class="flex">
                                <p><i class="fas fa-circle-dot" style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"></i><span style="color: <?= ($fetch_content['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"><?= $fetch_content['status']; ?></span></p>
                                <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
                            </div>
                            <img src="../upload/<?= $fetch_content['thumbnail']; ?>" alt="">
                            <h3 class="title"><?= $fetch_content['title']; ?></h3>
                            <a href="viewcontent.php?get_id=<?= $fetch_content['id']; ?>" class="bt">Xem bài học</a>
                            <form action="" class="flex-bt" method="POST">
                                <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                                <a href="updatecontent.php?get_id=<?= $fetch_content['id']; ?>" class="option-bt">Cập nhập</a>
                                <input type="submit" value="Xoá" name="delete_content" class="delete-bt">
                            </form>
                        </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">Không tìm thấy bài học nào hết!</p>';
                }
            } else {
                echo '<p class="empty">Tìm gì đi ní!</p>';
            }
            ?>
        </div>
    </section>
    <section class="danhsach">
        <h1 class="heading">Danh sách</h1>
        <div class="box-container">
            <?php
            if (isset($_POST['search_box']) or isset($_POST['search_bt'])) {
                $search_box = $_POST['search_box'];
                $select_danhsach = $conn->prepare("select * from `danhsach` where title LIKE '%{$search_box}%' and giasu_id = ? order by date desc");
                $select_danhsach->execute([$giasu_id]);
                if ($select_danhsach->rowCount() > 0) {
                    while ($fetch_danhsach = $select_danhsach->fetch(PDO::FETCH_ASSOC)) {
                        $danhsach_id = $fetch_danhsach['id'];
                        $count_content = $conn->prepare("select * from `content` where danhsach_id = ?");
                        $count_content->execute([$danhsach_id]);
                        $total_contents = $count_content->rowCount();

            ?>
                        <div class="box">
                            <div class="flex">
                                <div><i class="fas fa-circle-dot" style="color: <?= ($fetch_danhsach['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"></i><span style="color: <?= ($fetch_danhsach['status'] == 'Hoạt động') ? 'limegreen' : 'red'; ?>;"><?= $fetch_danhsach['status']; ?></span></div>
                                <div><i class="fas fa-calendar"></i><span><?= $fetch_danhsach['date']; ?></span></div>
                            </div>
                            <div class="thumb">
                                <span><?= $total_contents; ?></span>
                                <img src="../upload/<?= $fetch_danhsach['thumbnail']; ?>" alt="">
                            </div>
                            <h3 class="title"><?= $fetch_danhsach['title']; ?></h3>
                            <p class="desc"><?= $fetch_danhsach['desc']; ?></p>
                            <form action="" method="POST" class="flex-bt">
                                <input type="hidden" name="delete_id" value="<?= $danhsach_id; ?>">
                                <a href="updatedanhsach.php?get_id=<?= $danhsach_id; ?>" class="option-bt">Cập nhập</a>
                                <input type="submit" value="Xoá" name="delete_danhsach" class="delete-bt">
                            </form>
                            <a href="viewdanhsach.php?get_id=<?= $danhsach_id; ?>" class="bt">Xem danh sách</a>
                        </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">Không tìm thấy danh sách!</p>';
                }
            } else {
                echo '<p class="empty">Tìm gì đi ní!</p>';
            }
            ?>
        </div>
    </section>
    <script src="../admin.js"></script>
    <script>
        document.querySelector('.desc').forEach(content => {
            if (content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
        });
    </script>
</body>

</html>