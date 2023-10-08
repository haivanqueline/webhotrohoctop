<?php
include '../tp/connect.php';
if (isset($_COOKIE['giasu_id'])) {
    $giasu_id = $_COOKIE['giasu_id'];
} else {
    $giasu_id = '';
    header('location:dangnhap.php');
}
if (isset($_POST['delete_danhsach'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
    $verify_danhsach = $conn->prepare("select * from `danhsach` where id = ?");
    $verify_danhsach->execute([$delete_id]);
    if ($verify_danhsach->rowCount() > 0) {
        $fetch_thumb = $verify_danhsach->fetch(PDO::FETCH_ASSOC);
        $prev_thumb = $fetch_thumb['thumbnail'];
        if ($prev_thumb != '' && file_exists('../uploads/' . $prev_thumb)) {
            unlink('../uploads/' . $prev_thumb);
        }
        $delete_bookmark = $conn->prepare("delete from `bookmark` where danhsach_id = ?");
        $delete_bookmark->execute([$delete_id]);
        $delete_danhsach = $conn->prepare("delete from `danhsach` where id = ?");
        $delete_danhsach->execute([$delete_id]);
        $message[] = 'Danh sách đã xoá';
    } else {
        $message[] = 'Danh sách đã được xoá!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tất cả danh sách</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../admin.css">
</head>

<body>
    <?php include '../tp/adminheader.php' ?>
    <section class="danhsach">
        <h1 class="heading">Danh sách</h1>
        <div class="box-container">
            <div class="box" style="text-align: center;">
                <h3 class="title" style="padding-bottom: .7rem;">Tạo danh sách mới</h3>
                <a href="adddanhsach.php" class="bt">Thêm danh sách</a>
            </div>
            <?php
            $select_danhsach = $conn->prepare("select * from `danhsach` where giasu_id = ?");
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
                echo '<p class="empty">Không có danh sách nào hết!</p>';
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