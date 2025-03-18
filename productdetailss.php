<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<?php include('db.php'); ?>
<?php
// เชื่อมต่อฐานข้อมูล
$product = null;
$selectedingredientss = [];
// ตรวจสอบว่ามีการส่ง ID มาหรือไม่ (ใช้สำหรับแก้ไข)
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // ดึงข้อมูลสินค้า
    $query = "SELECT * FROM product WHERE id_product = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo 'ID สินค้าไม่ถูกต้อง';
        exit;
    }
    // ดึงข้อมูล ingredients ที่เชื่อมโยงกับสินค้า
    $sqlProductingredients = "SELECT id_ingredients FROM product_ingredients WHERE id_product = ?";
    $stmtProductingredients = $pdo->prepare($sqlProductingredients);
    $stmtProductingredients->execute([$product_id]);
    $selectedingredientss = $stmtProductingredients->fetchAll(PDO::FETCH_COLUMN);
}
// ดึงข้อมูลวัตถุดิบทั้งหมด
$sqlingredients = "SELECT * FROM ingredients";
$stmtingredients = $pdo->prepare($sqlingredients);
$stmtingredients->execute();
$ingredientss = $stmtingredients->fetchAll(PDO::FETCH_ASSOC);

?>



<body>
    <?php include('spinner.php'); ?>
    <?php include('nav.php'); ?>

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center pt-5 pb-3">
            <h1 class="display-4 text-white animated slideInDown mb-3">MPM HAPPY COFFEE</h1>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- About Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row img-twice position-relative h-100">
                        <div class="col-6">
                            <img class="img-fluid rounded" src="img/product/<?= htmlspecialchars($product['img1_product']); ?>" alt="">
                        </div>
                        <div class="col-6 align-self-end">
                            <img class="img-fluid rounded" src="img/product/<?= htmlspecialchars($product['img2_product']); ?>" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="h-100">

                        <p class="text-primary text-uppercase mb-2">รายละเอียดสินค้า</p>
                        <h1 class="display-6 mb-4"><?= isset($product['name_product']) ? htmlspecialchars($product['name_product']) : ''; ?> </h1>

                        <div class="detail1-container">
                            <?= $detail1_html; ?>
                        </div>
                    </div>
                    <a class="btn btn-primary rounded-pill py-3 px-5" href="https://liff.line.me/2004905932-ZvVLn72n">สั่งซื้อผลิตภัณฑ์</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- About End -->


    <!-- Team Start -->
    <div class="container-xxl py-6" id="Team">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="text-primary text-uppercase mb-2">ส่วนประกอบหลัก</p>
                <h1 class="display-6 mb-4">สารสกัดจากธรรมชาติที่สำคัญ</h1>
            </div>
            <div class="row g-4">
                <?php foreach ($ingredientss as $ingredients) : ?>
                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item text-center rounded overflow-hidden">
                            <img class="img-fluid" src="img/ingredients/<?= htmlspecialchars($ingredients['img_ingredients']); ?>" alt="" style="max-width: 50%;height: 50%;">
                            <div>
                                <div>
                                    <h5><?= htmlspecialchars($ingredients['name_ingredients']); ?></h5>
                                    <span><?= htmlspecialchars($ingredients['detail_ingredients']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item text-center rounded overflow-hidden">
                        <img class="img-fluid" src="img/logo/12.png" alt="" style="max-width: 50%;height: 50%;">
                        <div>
                            <div>
                                <h5>คอลลาเจน</h5>
                                <span> เสริมสร้างความกระจ่างใส & ช่วยฟื้นฟูดูแลสุขภาพผิวให้กระชับ เรียบเนียน
                                    ลดการเสื่อมสภาพของข้อต่อ เพิ่มความแข็งแรงให้กับกระดูกและข้อ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <?php
    // ตรวจสอบว่ามีข้อมูลและเป็น JSON ที่สามารถแปลงเป็นอาร์เรย์ได้
    $com_product_array = isset($product['com_product']) ? json_decode($product['com_product'], true) : [];
    $amount_product_array = isset($product['amount_product']) ? json_decode($product['amount_product'], true) : [];

    if (!empty($com_product_array) && !empty($amount_product_array)): ?>
        <div class="container">
            <h2 class="text-center">• ส่วนประกอบที่สำคัญใน 1 ซอง •</h2>
            <table class="table table-striped table-bordered">
                <tbody>
                    <?php foreach ($com_product_array as $index => $com_product): ?>
                        <tr>
                            <td><?= htmlspecialchars($com_product) ?></td>
                            <td><?= htmlspecialchars($amount_product_array[$index]) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="container text-center">
            <p>ไม่มีข้อมูลส่วนประกอบ</p>
        </div>
    <?php endif; ?>

    <!-- เรียกใช้ Bootstrap JavaScript -->


    <!-- Service Start -->
    <div class="container-xxl py-6" id="Service">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="text-primary text-uppercase mb-2">วีธีการบริโภคและเก็บรักษา</p>
                    <h1 class="display-6 mb-4">รับประกันความพึงพอใจ</h1>
                    <p class="mb-5">คืนสินค้าภายในเงื่อนไขที่บริษัทกำหนด
                    </p>
                    <div class="row gy-5 gx-4">
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-coffee text-white"></i>
                                </div>
                                <h5 class="mb-0">วิธีเตรียมและบริโภค</h5>
                            </div>
                            <?= isset($product['consumption_product']) ? htmlspecialchars($product['consumption_product']) : ''; ?>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.2s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-thermometer-half text-white"></i>
                                </div>
                                <h5 class="mb-0">วิธีการเก็บรักษา</h5>
                            </div>
                            <?= isset($product['keeping_product']) ? htmlspecialchars($product['keeping_product']) : ''; ?>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-exclamation-circle text-white"></i>
                                </div>
                                <h5 class="mb-0">ข้อควรระวัง</h5>
                            </div>
                            <?= isset($product['guard_product']) ? htmlspecialchars($product['guard_product']) : ''; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="row img-twice position-relative h-100">
                        <div class="col-6">
                            <img class="img-fluid rounded" src="/img/product/<?= htmlspecialchars($product['img4_product']); ?>" alt="">
                        </div>
                        <div class="col-6 align-self-end">
                            <img class="img-fluid rounded" src="/img/product/<?= htmlspecialchars($product['img5_product']); ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <h2 class="text-center"> <a class="btn btn-primary rounded-pill py-3 px-5" href="index.php">กลับหน้าหลัก</a></h2>
    </div>

    <!-- Service End -->
    <?php include('footer.php'); ?>
    <?php include('script.php'); ?>
</body>

</html>