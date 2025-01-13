<?php include 'head.php'; ?>
<?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM history";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$historys = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
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
                        <h1 class="h3 mb-2 text-gray-800">ประวัติความเป็นมา</h1>
                        
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">รายการ ประวัติความเป็นมา</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <a href="add_from/add_history.php" class="btn btn-primary mb-3">เพิ่มโปรโมชั่น</a>

                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Name (Thai)</th>
                                                <th>Details (Thai)</th>
                                                <th>Name (English)</th>
                                                <th>Details (English)</th>
                                                <th>edit/delete</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Name (Thai)</th>
                                                <th>Details (Thai)</th>
                                                <th>Name (English)</th>
                                                <th>Details (English)</th>
                                                <th>edit/delete</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($historys as $index => $history): ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($history['date_history'])); ?></td>
                                                    <td><?= htmlspecialchars($history['name_th_history']); ?></td>
                                                    <td><?= $history['detail_th_history']; ?></td>
                                                    <td><?= htmlspecialchars($history['name_en_history']); ?></td>
                                                    <td><?= $history['detail_en_history']; ?></td>
                                                    <td>
                                                        <a href="edit/edit_list_history.php?id=<?= $history['id_history']; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete/delete_history.php?id=<?= $history['id_history']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?');">
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

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>