<?php 
    if (isset($message)) {
        foreach($message as $message){
            echo'<div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
    }
?>



<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Yolostudy: Gia sư</a>
        <form action="searchpage.php" method="post" class="search-form">
            <input type="text" placeholder="Tìm kiếm ..." required maxlength="100" name="search_box">
            <button type="submit" class="fas fa-search" name="search_bt"></button>
        </form>
        <div class="icons">
            <div id="menu-bt" class="fas fa-bars"></div>
            <div id="search-bt" class="fas fa-search"></div>
            <div id="user-bt" class="fas fa-user"></div>
            <div id="toggle-bt" class="fas fa-sun"></div>
        </div>
        <div class="profile">
            <?php
            $select_profile = $conn->prepare("select * from `giasu` where id = ?");
            $select_profile->execute([$giasu_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

            ?>
                <img src="../upload/<?= $fetch_profile['image']; ?>" alt="">
                <h3><?= $fetch_profile['name']; ?></h3>
                <span><?= $fetch_profile['job']; ?></span>
                <a href="../admin/profile.php" class="bt">Xem hồ sơ</a>
                <div class="flex-bt">
                    <a href="../admin/dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="../admin/dangky.php" class="option-bt">Đăng ký</a>
                </div>
                <a href="../tp/adminlogout.php" onclick="return confirm('Bạn có muốn đăng xuất không!');" class="delete-bt">Đăng xuất</a>
            <?php
            } else {

            ?>
                <h3>Đăng nhập trước</h3>
                <div class="flex-bt">
                    <a href="../admin/dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="../admin/dangky.php" class="option-bt">Đăng ký</a>
                </div>
            <?php
            }
            ?>
        </div>
    </section>
</header>
<div class="side-bar">
    <div id="close-bar">
            <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
        $select_profile = $conn->prepare("select * from `giasu` where id = ?");
        $select_profile->execute([$giasu_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

        ?>
            <img src="../upload/<?= $fetch_profile['image']; ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span><?= $fetch_profile['job']; ?></span>
            <a href="../admin/profile.php" class="bt">Xem hồ sơ</a>
        <?php
        } else {

        ?>
            <h3>Đăng nhập trước</h3>
            <div class="flex-bt">
                <a href="../admin/dangnhap.php" class="option-bt">Đăng nhập</a>
                <a href="../admin/dangky.php" class="option-bt">Đăng ký</a>
            </div>
        <?php
        }
        ?>
    </div>
    <nav class="navbar">
        <a href="../admin/dashboard.php"><i class="fas fa-home"></i><span>Trang chủ</span></a>
        <a href="../admin/danhsach.php"><i class="fas fa-bars-staggered"></i><span>Danh sách</span></a>
        <a href="../admin/contents.php"><i class="fas fa-graduation-cap"></i><span>Nội dung</span></a>
        <a href="../admin/comments.php"><i class="fas fa-comment"></i><span>Bình luận</span></a>
        <a href="../tp/adminlogout.php" onclick="return confirm('Bạn có muốn đăng xuất không!');"><i class="fas fa-right-from-bracket"></i><span>Đăng xuất</span></a>
    </nav>
</div>