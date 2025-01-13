<?php include "head.php"; ?>

<?php

// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM contact WHERE id_contact = 1");
$stmt->execute();
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'navbar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">แก้ไขข้อมูลการติดต่อ</h1>
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
                            <form method="POST" action="save_edit/save_edit_contact.php" data-parsley-validate class="form-horizontal form-label-left">
                                <input type="hidden" name="id_contact" value="<?php echo htmlspecialchars($id_contact); ?>">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address_th_contact">ที่อยู่ (ไทย)</label>
                                            <textarea class="form-control" id="address_th_contact" name="address_th_contact" required><?php echo htmlspecialchars($contact['address_th_contact']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="email_contact">อีเมล</label>
                                            <input type="email" class="form-control" id="email_contact" name="email_contact" value="<?php echo htmlspecialchars($contact['email_contact']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="open_th_contact">เวลาเปิดทำการ (ไทย)</label>
                                            <input type="text" class="form-control" id="open_th_contact" name="open_th_contact" value="<?php echo htmlspecialchars($contact['open_th_contact']); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address_en_contact">ที่อยู่ (อังกฤษ)</label>
                                            <textarea class="form-control" id="address_en_contact" name="address_en_contact" required><?php echo htmlspecialchars($contact['address_en_contact']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_contact">โทรศัพท์</label>
                                            <input type="text" class="form-control" id="phone_contact" name="phone_contact" value="<?php echo htmlspecialchars($contact['phone_contact']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="open_en_contact">เวลาเปิดทำการ (อังกฤษ)</label>
                                            <input type="text" class="form-control" id="open_en_contact" name="open_en_contact" value="<?php echo htmlspecialchars($contact['open_en_contact']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="map_link_contact">ลิงก์แผนที่</label>
                                            <input type="text" class="form-control" id="map_link_contact" name="map_link_contact" value="<?php echo htmlspecialchars($contact['map_link_contact']); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="line_contact">LINE</label>
                                            <input type="text" class="form-control" id="line_contact" name="line_contact" value="<?php echo htmlspecialchars($contact['line_contact']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="facebook_contact">Facebook</label>
                                            <input type="text" class="form-control" id="facebook_contact" name="facebook_contact" value="<?php echo htmlspecialchars($contact['facebook_contact']); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="youtube_contact">YouTube</label>
                                            <input type="text" class="form-control" id="youtube_contact" name="youtube_contact" value="<?php echo htmlspecialchars($contact['youtube_contact']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                <a href="contact.php" class="btn btn-secondary">ยกเลิก</a>
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