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
        <a href="home.php" class="logo">Yolostudy<i class="fa-solid fa-check"></i></a>
        <form action="searchkhoahoc.php" method="post" class="search-form">
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
            $select_profile = $conn->prepare("select * from `user` where id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
                <img src="upload/<?= $fetch_profile['image']; ?>" alt="">
                <h3><?= $fetch_profile['name']; ?></h3>
                <span>Học sinh</span>
                <a href="../profile.php" class="bt">Xem hồ sơ</a>
                <div class="flex-bt">
                    <a href="../dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="../dangky.php" class="option-bt">Đăng ký</a>
                </div>
                <a href="../tp/userlogout.php" onclick="return confirm('Bạn có muốn đăng xuất không!');" class="delete-bt">Đăng xuất</a>
            <?php
            } else {

            ?>
                <h3>Đăng nhập trước</h3>
                <div class="flex-bt">
                    <a href="../dangnhap.php" class="option-bt">Đăng nhập</a>
                    <a href="../dangky.php" class="option-bt">Đăng ký</a>
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
        $select_profile = $conn->prepare("select * from `user` where id = ?");
        $select_profile->execute([$user_id]);
        if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

        ?>
            <img src="upload/<?= $fetch_profile['image']; ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span>Học sinh</span>
            <a href="../profile.php" class="bt">Xem hồ sơ</a>
        <?php
        } else {

        ?>
            <h3>Đăng nhập trước</h3>
            <div class="flex-bt">
                <a href="../dangnhap.php" class="option-bt">Đăng nhập</a>
                <a href="../dangky.php" class="option-bt">Đăng ký</a>
            </div>
        <?php
        }
        ?>
    </div>
    <nav class="navbar">
        <a href="../home.php"><i class="fas fa-home"></i><span>Trang chủ</span></a>
        <a href="../about.php"><i class="fas fa-question"></i><span>Về chúng tôi</span></a>
        <a href="../khoahoc.php"><i class="fas fa-graduation-cap"></i><span>Khóa học</span></a>
        <a href="../giasu.php"><i class="fas fa-chalkboard-user"></i><span>Gia sư</span></a>
        <a href="../lienhe.php"><i class="fas fa-headset"></i><span>Liên hệ</span></a>
    </nav>
</div>