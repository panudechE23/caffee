<?php include "head.php"; ?>

<?php
// Get business ID from URL
$id_business = $_GET['id'] ?? null;

if (!$id_business) {
    $_SESSION['error'] = 'ไม่พบ ID ที่ระบุ';
    header('Location: ../table_business.php');
    exit();
}

// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM business WHERE id_business = :id_business");
$stmt->bindParam(':id_business', $id_business, PDO::PARAM_INT);
$stmt->execute();
$business = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$business) {
    $_SESSION['error'] = 'ไม่พบข้อมูลธุรกิจ';
    header('Location: ../table_business.php');
    exit();
}
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'navbar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">ธุรกิจ</h1>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">แก้ไขรายการ ธุรกิจ</h6>
                        </div>
                        <div class="card-body">
                        <form action="../save_edit/save_edit_business.php" method="post" enctype="multipart/form-data" >
                        <input type="hidden" name="id_business" value="<?php echo $business['id_business']; ?>">
                                <div class="col mb-3">
                                    <label for="img_business" class="form-label">รูปภาพธุรกิจ</label>
                                    <input type="file" class="form-control" id="img_business" name="img_business">
                                    <img src="../../img/business/<?php echo $business['img_business']; ?>" alt="Business Image" class="img-fluid mt-2" style="width: 100px; height: auto;">
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="name_th_business" class="form-label">ชื่อธุรกิจ (ภาษาไทย)</label>
                                        <input type="text" class="form-control" id="name_th_business" name="name_th_business" value="<?php echo $business['name_th_business']; ?>">
                                    </div>
                                    <div class="col mb-3">
                                        <label for="name_en_business" class="form-label">ชื่อธุรกิจ (ภาษาอังกฤษ)</label>
                                        <input type="text" class="form-control" id="name_en_business" name="name_en_business" value="<?php echo $business['name_en_business']; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="detail_th_business" class="form-label">รายละเอียดธุรกิจ (ภาษาไทย)</label>
                                        <textarea class="form-control" id="detail_th_business" name="detail_th_business" rows="3"><?php echo $business['detail_th_business']; ?></textarea>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="detail_en_business" class="form-label">รายละเอียดธุรกิจ (ภาษาอังกฤษ)</label>
                                        <textarea class="form-control" id="detail_en_business" name="detail_en_business" rows="3"><?php echo $business['detail_en_business']; ?></textarea>
                                    </div>
                                </div>
                                <a href="../page_business.php" class="btn btn-secondary">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                               
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../footer.php'; ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>