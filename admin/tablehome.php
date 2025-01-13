<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">ตารางรายการ banner หน้าหลัก</h1>
<br>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>หัวเรื่อง ภาษาไทย</th>
                        <th>รายละเอียด ภาษาไทย</th>
                        <th>หัวเรื่อง ภาษาอังกฤษ</th>
                        <th>รายละเอียด ภาษาอังกฤษ</th>
                        <th>รูป</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>หัวเรื่อง ภาษาไทย</th>
                        <th>รายละเอียด ภาษาไทย</th>
                        <th>หัวเรื่อง ภาษาอังกฤษ</th>
                        <th>รายละเอียด ภาษาอังกฤษ</th>
                        <th>รูป</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($homes as $home): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($home['text1_th_home']); ?></td>
                            <td><?php echo htmlspecialchars($home['text2_th_home']); ?></td>
                            <td><?php echo htmlspecialchars($home['text1_en_home']); ?></td>
                            <td><?php echo htmlspecialchars($home['text2_en_home']); ?></td>
                            <td>
                                <img src="../../img/home/<?php echo $home['id_home']; ?>/<?php echo htmlspecialchars($home['img_home']); ?>" alt="Image" width="100">
                            </td>
                            <td>
                                <a href="../edit/edit_list_home.php?id=<?php echo $home['id_home']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="../delete/delete_home.php?id=<?php echo $home['id_home']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>