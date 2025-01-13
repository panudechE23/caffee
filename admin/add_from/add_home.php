<?php include 'head.php'; ?>
<?php

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM home";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$homes = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array
?>

<body id="page-top">

    <div id="wrapper">
        <?php include 'navbar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">banner หน้าหลัก</h1>
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

                    <!-- Upload Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">เพิ่มข้อมูล banner หน้าหลัก</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="../add_save/add_list_home.php">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="text1_th">หัวข้อ ภาษาไทย</label>
                                            <input type="text" class="form-control" id="text1_th" name="text1_th" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="text2_th">หัวข้อ ภาษาอังกฤษ</label>
                                            <input type="text" class="form-control" id="text2_th" name="text2_th" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="text1_en">รายละเอียด ภาษาไทย</label>
                                            <input type="text" class="form-control" id="text1_en" name="text1_en" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="text2_en">รายละเอียด ภาษาอังกฤษ</label>
                                            <input type="text" class="form-control" id="text2_en" name="text2_en" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="vdo_home">youtube line</label>
                                            <input type="text" class="form-control" id="vdo_home" name="vdo_home" required>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Upload รูป</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include '../tablehome.php'; ?>
                <?php include '../footer.php'; ?>
            </div>
        </div>
    </div>


</body>

</html>