    <?php include "head.php"; ?>

    <?php
    $id_home = $_GET['id'];

    // ดึงข้อมูลจากฐานข้อมูลตาม ID
    $stmt = $pdo->prepare("SELECT * FROM home WHERE id_home = :id_home");
    $stmt->bindParam(':id_home', $id_home, PDO::PARAM_INT);
    $stmt->execute();
    $home = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>

    <body id="page-top">
        <div id="wrapper">
            <?php include 'navbar.php'; ?>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php include 'nav.php'; ?>
                    <div class="container-fluid">
                        <h1 class="h3 mb-4 text-gray-800">Edit Information</h1>
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
                                <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="../save_edit/save_edit_home.php" data-parsley-validate class="form-horizontal form-label-left">
                                    <input type="hidden" name="id_home" value="<?php echo htmlspecialchars($id_home); ?>">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="text1_th">หัวเรื่อง ภาษาไทย</label>
                                                <input type="text" class="form-control" id="text1_th" name="text1_th" value="<?php echo htmlspecialchars($home['text1_th_home']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="textภาษาไทย_th">รายละเอียด ภาษาไทย</label>
                                                <input type="text" class="form-control" id="text2_th" name="text2_th" value="<?php echo htmlspecialchars($home['text2_th_home']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="text1_en">หัวเรื่อง ภาษาอังกฤษ</label>
                                                <input type="text" class="form-control" id="text1_en" name="text1_en" value="<?php echo htmlspecialchars($home['text1_en_home']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="text2_en">รายละเอียด ภาษาอังกฤษ</label>
                                                <input type="text" class="form-control" id="text2_en" name="text2_en" value="<?php echo htmlspecialchars($home['text2_en_home']); ?>" required>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                            <label for="vdo_home">youtube line</label>
                                            <input type="text" class="form-control" id="vdo_home" name="vdo_home" value="<?php echo htmlspecialchars($home['vdo_home']); ?>" required>
                                        </div>
                                    <div class="form-group">
                                        <label for="image">Upload เปลียนรูปใหม่</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <?php if (!empty($home['img_home'])): ?>
                                            <img src="../../img/home/<?php echo htmlspecialchars($id_home); ?>/<?php echo htmlspecialchars($home['img_home']); ?>" alt="Current Image" width="100" class="mt-2">
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="addhome.php" class="btn btn-secondary">Cancel</a>
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