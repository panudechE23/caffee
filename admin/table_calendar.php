<?php include 'head.php'; ?>
<?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM calendar";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
?>

<body id="page-top">
    <style>
        .text-truncate {
            white-space: nowrap;
            /* แสดงข้อความในบรรทัดเดียว */
            overflow: hidden;
            /* ซ่อนข้อความที่เกิน */
            text-overflow: ellipsis;
            /* แสดง ... เมื่อข้อความถูกตัด */
        }
    </style>

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
                        <h1 class="h3 mb-2 text-gray-800">กิจกรรม</h1>
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">รายการ กิจกรรม</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <a href="add_from/add_calendar.php" class="btn btn-primary mb-3">เพิ่ม รายการกิจกรรม </a>
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ชื่อกิจกรรม</th>
                                                <th>วันที่จัดกิจกรรม</th>
                                                <th>เวลา</th>
                                                <th>วิทยากร</th>
                                                <th>สถานที่</th>
                                                <th>หมายเหตุ</th>
                                                <th>การจัดการ</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>ชื่อกิจกรรม</th>
                                                <th>วันที่จัดกิจกรรม</th>
                                                <th>เวลา</th>
                                                <th>วิทยากร</th>
                                                <th>สถานที่</th>
                                                <th>หมายเหตุ</th>
                                                <th>แก้ไข/ลบ</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($calendars as $index => $calendar): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td class="text-truncate" style="max-width: 150px;">
                                                        <?php echo htmlspecialchars($calendar['name_th_calendar']); ?>
                                                    </td>

                                                    <td><?php echo htmlspecialchars($calendar['date_calendar']); ?></td>
                                                    <td><?php echo htmlspecialchars($calendar['start_time_calendar']); ?> - <?php echo htmlspecialchars($calendar['end_time_calendar']); ?></td>
                                                    <td>
                                                        <?php echo htmlspecialchars($calendar['speaker_th_calendar']); ?><br>
                                                  
                                                    </td>
                                                    <td class="text-truncate" style="max-width: 150px;">
                                                        <?php echo htmlspecialchars($calendar['location_th_calendar']); ?>
                                                    </td>
                                                    <td class="text-truncate" style="max-width: 200px;">
                                                        <?php echo htmlspecialchars($calendar['notes_th_calendar']); ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit/edit_list_calendar.php?id=<?= $calendar['id_calendar']; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete/delete_calendar.php?id=<?= $calendar['id_calendar']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?');">
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
    <script src="js/demo/datatables-demo.js"></script>


</body>

</html>