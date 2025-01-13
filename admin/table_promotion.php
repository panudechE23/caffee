<?php include 'head.php'; ?>
<?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM promotion";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
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
                        <h1 class="h3 mb-2 text-gray-800">โปรโมชั่น</h1>

                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">ตารางโปรโมชั่น</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <a href="add_from/add_promotion.php" class="btn btn-primary mb-3">เพิ่มโปรโมชั่น</a>


                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ชื่อ ไทย</th>
                                                <th>รายละเอียด ไทย</th>
                                                <th>ชื่อ อังกฤษ</th>
                                                <th>รายละเอียด อังกฤษ</th>
                                                <th>เริ่ม - สินสุด</th>
                                                <th>รูป</th>
                                                <th>แก้ไข/ลบ</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>ชื่อ ไทย</th>
                                                <th>รายละเอียด ไทย</th>
                                                <th>ชื่อ อังกฤษ</th>
                                                <th>รายละเอียด อังกฤษ</th>
                                                <th>เริ่ม - สินสุด</th>
                                                <th>รูป</th>
                                                <th>แก้ไข/ลบ</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($promotions as $index => $promotion): ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td><?= htmlspecialchars($promotion['name_th_promotion']); ?></td>
                                                    <td><?= $promotion['detail_th_promotion']; ?></td>
                                                    <td><?= htmlspecialchars($promotion['name_en_promotion']); ?></td>
                                                    <td><?= $promotion['detail_en_promotion']; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($promotion['start_date_promotion'])); ?> - <?= date('d-m-Y', strtotime($promotion['end_date_promotion'])); ?></td>
                                                    <td>
                                                        <img src="../img/promotion/<?= $promotion['id_promotion']; ?>/<?= htmlspecialchars($promotion['img_1_promotion']); ?>" alt="Image" width="100">
                                                    </td>
                                                    <td>
                                                        <a href="edit/edit_list_promotion.php?id=<?= $promotion['id_promotion']; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete/delete_promotion.php?id=<?= $promotion['id_promotion']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?');">
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

                <?php include 'footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/datatables-demo.js"></script>

</html>