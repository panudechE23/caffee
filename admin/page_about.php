<?php include "head.php"; ?>

<?php

// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM about WHERE id_about = 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'navbar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">แก้ไขข้อมูลเกียวกับเรา</h1>
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
                            <h6 class="m-0 font-weight-bold text-primary">แก้ไขข้อมูล</h6>
                        </div>
                        <div class="card-body">
                            <form action="save_edit/save_edit_about.php" method="post" enctype="multipart/form-data" >
                                <input type="hidden" name="id_about" value="<?php echo $about['id_about']; ?>">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_th_about">ชื่อเรื่อง (TH)</label>
                                            <input type="text" class="form-control" id="name_th_about" name="name_th_about" value="<?php echo htmlspecialchars($about['name_th_about']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="detail_th_about">รายละเอียด (TH)</label>
                                            <textarea class="form-control" id="detail_th_about" name="detail_th_about" required><?php echo htmlspecialchars($about['detail_th_about']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="vdo_about">ลิงก์วิดีโอ</label>
                                            <input type="text" class="form-control" id="vdo_about" name="vdo_about" value="<?php echo htmlspecialchars($about['vdo_about']); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_en_about">ชื่อเรื่อง (EN)</label>
                                            <input type="text" class="form-control" id="name_en_about" name="name_en_about" value="<?php echo htmlspecialchars($about['name_en_about']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="detail_en_about">รายละเอียด (EN)</label>
                                            <textarea class="form-control" id="detail_en_about" name="detail_en_about" required><?php echo htmlspecialchars($about['detail_en_about']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="img_about">รูปภาพ</label>
                                            <input type="file" class="form-control" id="img_about" name="img_about" value="<?php echo htmlspecialchars($about['img_about']); ?>">
                                            <?php if (!empty($about['img_about'])): ?>
                                                <img src="../img/about/<?=($about['img_about']); ?>" alt="About Image" class="img-fluid mt-2" style="width: 100px; height: auto;">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                <a href="about.php" class="btn btn-secondary">ยกเลิก</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>