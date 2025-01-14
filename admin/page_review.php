<?php include 'head.php'; ?>
<?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM review";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
?>

<body id="page-top">

    <div id="wrapper">
        <?php include 'navbar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
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
                    <div class="container-fluid">
                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-gray-800">ภาพโมเมนต์พิเศษ</h1>
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">รายการ ภาพโมเมนต์พิเศษ</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="card-body">
                                        <h6 class="card-title">เพิ่ม รูปภาพ</h6>
                                        <form method="POST" enctype="multipart/form-data" action="add_save/add_list_review.php">
                                            <div class="form-group">
                                                <label for="vdo">Upload img</label>
                                                <input type="file" class="form-control" id="img_review" name="img_review" accept="image/*" required="ใส่รูปภาพ">
                                            </div>
                                            <div class="row">
                                            <div class="col form-group">
                                                <label for="name_review">name</label>
                                                <input type="text" class="form-control" id="name_review" name="name_review" required="ใส่ชื่อ">
                                            </div>
                                            
                                            <div class="col form-group">
                                                <label for="position_review">ตำแหน่ง</label>
                                                <input type="text" class="form-control" id="position_review" name="position_review" required="ใส่ตำแหน่ง">
                                            </div>
                                            <div class="col form-group">
                                                <label for="review">รีวิว</label>
                                                <input type="text" class="form-control" id="review" name="review" required="ใส่รีวิว">
                                            </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">บันทึก</button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>รูปภาพ</th>
                                                        <th>ชื่อ</th>
                                                        <th>ตำแหน่ง</th>
                                                        <th>รีวิว</th>
                                                        <th>แก้ไข/ลบ</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>รูปภาพ</th>
                                                        <th>ชื่อ</th>
                                                        <th>ตำแหน่ง</th>
                                                        <th>รีวิว</th>
                                                        <th>แก้ไข/ลบ</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php foreach ($reviews as $index => $review): ?>
                                                        <tr>
                                                            <td><?= $index + 1; ?></td>
                                                            <td>
                                                                <img src="../img/review/<?= htmlspecialchars($review['img_review']); ?>" alt="Review Image" class="img-fluid mt-2" style="width: 100px; height: auto;">
                                                            </td>
                                                            <td><?= $review['name_review']; ?></td>
                                                            <td><?= $review['position_review']; ?></td>
                                                            <td><?= $review['review']; ?></td>
                                                            <td>
                                                                <a href="edit/edit_review.php?id=<?= $review['id_review']; ?>" class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="delete/delete_review.php?id=<?= $review['id_review']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?');">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>