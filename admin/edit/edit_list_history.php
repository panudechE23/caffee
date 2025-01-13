<?php include 'head.php'; ?>
<?php


// Get history ID from URL
$id_history = $_GET['id'];

// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM history WHERE id_history = :id_history");
$stmt->bindParam(':id_history', $id_history, PDO::PARAM_INT);
$stmt->execute();
$history = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'navbar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">ประวัติความเป็นมา</h1>
                    <!-- Show error or success messages -->
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

                    <!-- Edit Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">แก้ไข ประวัติความเป็นมา</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="../save_edit/save_edit_history.php">
                            <input type="hidden" name="id_history" value="<?php echo htmlspecialchars($id_history); ?>">
                                <!-- name -->
                                <h6 class="m-0 font-weight-bold text-primary">ชื่อ</h6>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_th_history">ชื่อ ไทย</label>
                                            <input type="text" class="form-control" id="name_th_history" name="name_th_history" value="<?php echo $history['name_th_history']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_en_history">ชื่อ อังกฤษ</label>
                                            <input type="text" class="form-control" id="name_en_history" name="name_en_history" value="<?php echo $history['name_en_history']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- name -->
                                
                                <!-- detail -->
                                <h6 class="m-0 font-weight-bold text-primary">รายละเอียด</h6>
                                <div class="row">
                                    <div class="col form-group">
                                        <label for="detail_th_history">รายละเอียด ไทย</label>
                                        <textarea class="form-control" name="detail_th_history" id="detail" rows="5"><?php echo $history['detail_th_history']; ?></textarea>
                                    </div>
                                    <div class="col form-group">
                                        <label for="detail_en_history">รายละเอียด อังกฤษ</label>
                                        <textarea class="form-control" name="detail_en_history" id="detail" rows="5"><?php echo $history['detail_en_history']; ?></textarea>
                                    </div>
                                </div>
                                <!-- detail -->
                                
                                <!-- start date -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h6 class="m-0 font-weight-bold text-primary">เวลาประวัติ</h6>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group" id="simple-date3">
                                                    <label for="date_history">date</label>
                                                    <div class="input-group date">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="date_history" name="date_history" value="<?php echo date('d/m/Y', strtotime($history['date_history'])); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- start date -->
                                <a href="../table_history.php" class="btn btn-secondary">ย้อนกลับ</a>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../footer.php'; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="vendor/select2/dist/js/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <script src="vendor/clock-picker/clockpicker.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea#detail',
            plugins: 'advlist autolink lists link image charmap print preview anchor code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | fullscreen preview | code',
            menubar: 'file edit view insert format tools table help',
            height: 300,
            branding: false,
            automatic_uploads: true,
            file_picker_types: 'image',
            paste_data_images: true,
            images_file_types: 'jpg,svg,webp',
            image_title: true,
            file_picker_callback: (cb, value, meta) => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'img/*');

                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];

                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const id = 'blobid' + (new Date()).getTime();
                        const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        const base64 = reader.result.split(',')[1];
                        const blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    });
                    reader.readAsDataURL(file);
                });

                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        $(document).ready(function() {
            $('.select2-single').select2();

            // Date Picker Initialization
            $('#start_date_history').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
</body>
</html>
