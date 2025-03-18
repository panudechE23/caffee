 <!-- Carousel Start -->
 <?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM hero";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$heros = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
?>
 <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s" id="home">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <!-- <img class="img-fluid" src="img/carousel-1.jpg"> -->
                <video class="img-fluid" playsinline autoplay muted loop src="img/coffee7.mp4" type="video/mp4"></video>
                <!-- <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-8">
                                <p class="text-primary text-uppercase fw-bold mb-2">// The Best Bakery</p>
                                <h1 class="display-1 text-light mb-4 animated slideInDown">We Bake With Passion</h1>
                                <p class="text-light fs-5 mb-4 pb-3">Vero elitr justo clita lorem. Ipsum dolor sed stet sit diam rebum ipsum.</p>
                                <a href="" class="btn btn-primary rounded-pill py-3 px-5">Read More</a>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="owl-carousel-item position-relative">
                <video class="img-fluid" playsinline autoplay muted loop src="img/coffee5.mp4" type="video/mp4"></video>
            </div>
            <?php foreach ($heros as $index => $hero) : ?>
            <div class="owl-carousel-item position-relative">
                <video class="img-fluid" playsinline autoplay muted loop src="vdo/<?= ($hero['vdo_hero']); ?>" type="video/mp4"></video>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Carousel End -->
     