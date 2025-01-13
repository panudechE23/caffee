<?php
include 'head.php';

// Get executives ID from URL
$id_executive = $_GET['id'];

// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM executives WHERE id_executive = :id_executive");
$stmt->bindParam(':id_executive', $id_executive, PDO::PARAM_INT);
$stmt->execute();
$executive = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$executive) {
    $_SESSION['error'] = 'ไม่พบข้อมูลที่ต้องการแก้ไข';
    header('Location: table_executives.php');
    exit();
}
?>


<body id="page-top">

    <div id="wrapper">
        <?php include 'navbar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">ผู้บริหาร</h1>
                    <!-- Show error or success messages -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">แก้ไขรายชื่อ ผู้บริหาร</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="../save_edit/save_edit_executives.php">
                            <input type="hidden" name="id" value="<?php echo $executive['id_executive']; ?>">                               

                                <!-- อัปโหลดรูปภาพใหม่ -->
                                <div class="form-group">
                                    <label for="image">รูปประจำตัว</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                </div>
                                <!-- แสดงรูปภาพปัจจุบัน -->
                                <div class="form-group">                    
                                    <?php if (!empty($executive['img_executive'])): ?>
                                        <img src="../../img/executives/<?php echo $executive['id_executive']; ?>/<?php echo htmlspecialchars($executive['img_executive']); ?>" alt="Current Image" width="150">
                                    <?php else: ?>
                                        <p>ไม่มีรูปภาพ</p>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_th_executive">ชื่อ (ภาษาไทย)</label>
                                            <input type="text" class="form-control" id="name_th_executive" name="name_th_executive" value="<?php echo $executive['name_th_executive']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="position_th_executive">ตำแหน่ง (ภาษาไทย)</label>
                                            <input type="text" class="form-control" id="position_th_executive" name="position_th_executive" value="<?php echo $executive['position_th_executive']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_en_executive">ชื่อ (ภาษาอังกฤษ)</label>
                                            <input type="text" class="form-control" id="name_en_executive" name="name_en_executive" value="<?php echo $executive['name_en_executive']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="position_en_executive">ตำแหน่ง (ภาษาอังกฤษ)</label>
                                            <input type="text" class="form-control" id="position_en_executive" name="position_en_executive" value="<?php echo $executive['position_en_executive']; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php include '../footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>