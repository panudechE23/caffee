<?php
include 'head.php';
$id_home = $_GET['id'];
// ดึงข้อมูลจากฐานข้อมูลตาม ID
$stmt = $pdo->prepare("SELECT * FROM socialactivities WHERE id_socialactivities= :id_socialactivities");
$stmt->bindParam(':id_socialactivities', $id_home, PDO::PARAM_INT);
$stmt->execute();
$socialactivities = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<link href="../vendor/clock-picker/clockpicker.css" rel="stylesheet">
<link href="../vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="../vendor/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css" rel="stylesheet">

<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'navbar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'nav.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">กิจกรรมเพื่อสังคม</h1>
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
                            <h6 class="m-0 font-weight-bold text-primary">แก้ไข กิจกรรมเพื่อสังคม</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="../save_edit/save_edit_socialactivities.php">
                                <input type="hidden" name="id_socialactivities" value="<?php echo $socialactivities['id_socialactivities']; ?>">
                                <!-- name -->
                                <h6 class="m-0 font-weight-bold text-primary">ชื่อ</h6>
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name_th_socialactivities">ชื่อ ไทย</label>
                                            <input type="text" class="form-control" id="name_th_socialactivities" name="name_th_socialactivities" value="<?php echo $socialactivities['name_th_socialactivities']; ?>" placeholder="ชื่อ th">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group ">
                                            <label for="name_en_socialactivities">ชื่อ อังกฤษ</label>
                                            <input type="text" class="form-control" id="name_en_socialactivities" name="name_en_socialactivities" value="<?php echo $socialactivities['name_en_socialactivities']; ?>" placeholder="ชื่อ en">
                                        </div>
                                    </div>
                                </div>
                                <!-- name -->
                                <!-- img -->
                                <h6 class="m-0 font-weight-bold text-primary">รูป</h6>
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="img_1_socialactivities">รูป1</label>
                                            <input type="file" class="form-control" id="img_1_socialactivities" name="img_1_socialactivities" aria-describedby="inputGroupFileAddon01" aria-label="รูป1" placeholder="รูป1">
                                            <?php if (!empty($socialactivities['img_1_socialactivities'])): ?>
                                                <img src="../../img/socialactivities/<?php echo $socialactivities['id_socialactivities']; ?>/<?php echo $socialactivities['img_1_socialactivities']; ?>" alt="รูป1" width="100" style="padding-top: 3px;">
                                            <?php else: ?>
                                                <p>ไม่มีรูป</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="img_2_socialactivities">รูป2</label>
                                            <input type="file" class="form-control" id="img_2_socialactivities" name="img_2_socialactivities" aria-describedby="inputGroupFileAddon02" aria-label="รูป2" placeholder="รูป2">
                                            <?php if (!empty($socialactivities['img_2_socialactivities'])): ?>
                                                <img src="../../img/socialactivities/<?php echo $socialactivities['id_socialactivities']; ?>/<?php echo $socialactivities['img_2_socialactivities']; ?>" alt="รูป2" width="100" style="padding-top: 3px;">
                                            <?php else: ?>
                                                <p>ไม่มีรูป</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="img_3_socialactivities">รูป3</label>
                                            <input type="file" class="form-control" id="img_3_socialactivities" name="img_3_socialactivities[]" aria-describedby="inputGroupFileAddon03" aria-label="รูป3" placeholder="รูป3" multiple>
                                            <?php
                                            $images = json_decode($socialactivities['img_3_socialactivities'], true);
                                            if (!empty($images)) {
                                                foreach ($images as $image) {
                                                    echo '<img src="../../img/socialactivities/' . $socialactivities['id_socialactivities'] . '/' . $image . '" alt="รูป3" width="100" style="padding-top: 3px;">';
                                                }
                                            } else {
                                                echo '<p>ไม่มีรูป</p>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- img -->
                                <!-- detail -->

                                <h6 class="m-0 font-weight-bold text-primary">รายละเอียด</h6>
                                <div class="row ">
                                    <div class="col form-group">
                                        <label for="detail_th_socialactivities">รายละเอียด ไทย</label>
                                        <textarea class="form-control" name="detail_th_socialactivities" id="detail" rows="5"><?php echo $socialactivities['detail_th_socialactivities']; ?></textarea>
                                    </div>
                                    <div class="col form-group">
                                        <label for="detail_en_socialactivities">รายละเอียด อังกฤษ</label>
                                        <textarea class="form-control" name="detail_en_socialactivities" id="detail" rows="5"><?php echo $socialactivities['detail_en_socialactivities']; ?></textarea>
                                    </div>
                                </div>
                                <!-- detail -->
                               
                                <a href="../table_socialactivities.php" class="btn btn-secondary">ย้อนกลับ</a>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                              
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../footer.php'; ?>
        </div>
    </div>
    <!-- <?php include '../script.php'; ?> -->

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js" referrerpolicy="origin"></script>

<script src="../vendor/select2/dist/js/select2.min.js"></script>
<script src="../vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
<script src="../vendor/clock-picker/clockpicker.js"></script>
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
        /* enable title field in the Image dialog*/
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
</script>

<script>
    $(document).ready(function() {
        $('.select2-single').select2();

        // Date Picker Initialization
        $('#start_date_socialactivities').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#end_date_socialactivities').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

</html>