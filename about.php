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
    <title>Yolostudy: Về chúng tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../s.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>
    <?php include 'tp/userheader.php'; ?>

    <section class="about">
        <div class="row">
            <div class="image">
                <img src="logohondai.ico" alt="">
            </div>
            <div class="content">
                <h3>Lý do chọn chúng tôi?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae quas ab voluptatem qui porro
                    reiciendis?</p>
                <a href="khoahoc.php" class="inline-bt">Khóa học của chúng tôi</a>
            </div>
        </div>
        <div class="box-container">
            <div class="box">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <h3>Với hơn 10000</h3>
                    <span>Khóa học trực tuyến</span>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-user-graduate"></i>
                <div>
                    <h3>Với hơn 100000</h3>
                    <span>Học sinh, sinh viên</span>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-chalkboard-user"></i>
                <div>
                    <h3>Với hơn 3000</h3>
                    <span>Gia sư chuyên nghiệp đẳng cấp</span>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-briefcase"></i>
                <div>
                    <h3>99,9%</h3>
                    <span>Thành học sinh giỏi, khá</span>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews">
        <h1 class="heading">Đánh giá của người dùng</h1>
        <div class="box-container">
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
            <div class="box">
                <div class="user">
                    <img src="logohondai.ico" alt="">
                    <div>
                        <h3>Hari</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis magnam et architecto accusamus
                    iure minus corporis vero porro suscipit cupiditate?</p>
            </div>
        </div>
    </section>

    <?php include 'tp/chatbot.php'; ?>
    <script src="s.js" defer></script>
</body>

</html>