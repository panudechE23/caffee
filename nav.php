 <!-- Navbar Start -->
 <!-- Footer Start -->
 <?php

    $stmt = $pdo->prepare("SELECT * FROM contact WHERE id_contact = 1");
    $stmt->execute();
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM product";
$stmt = $pdo->query($query); // ใช้ตัวแปร $query ที่กำหนดไว้ด้านบน
$products = $stmt->fetchAll(PDO::FETCH_ASSOC); // เก็บข้อมูลทั้งหมดในรูปแบบ array

    ?>



 <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
     <a href="index.html" class="navbar-brand ms-4 ms-lg-0">
         <h1 class="text-primary m-0">HAPPY COFFEE</h1>
     </a>
     <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarCollapse">
         <div class="navbar-nav mx-auto p-4 p-lg-0">
             <a href="index.php" class="nav-item nav-link active">หน้าแรก</a>
             <a href="index.php#about" class="nav-item nav-link">เกี่ยวกับเรา</a>
             <a href="index.php#Testimonial" class="nav-item nav-link">รีวิว</a>
             <div class="nav-item dropdown">
                 <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">ผลิตภัณฑ์</a>
                 <div class="dropdown-menu m-0">
                     <?php foreach ($products as $product) : ?>
                         <a class="dropdown-item" href="productdetails.php?id_product=<?php echo $product['id_product']; ?>"><?php echo $product['name_product']; ?></a>
                     <?php endforeach; ?>
                     <a href="productdetails.php" class="dropdown-item">HAPPY COFFEE</a>
                     <a href="productdetails.php" class="dropdown-item">HAPPY COFFEE GOLD</a>
                     <a href="productdetails.php" class="dropdown-item">HAPPY COFFEE MAX</a>
                 </div>
             </div>
         </div>
         <!-- <div class=" d-none d-lg-flex">
                <div class="flex-shrink-0 btn-lg-square border border-light rounded-circle">
                    <i class="fa fa-phone text-primary"></i>
                </div>
                <div class="ps-3">
                    <small class="text-primary mb-0">โทรหาเรา</small>
                    <p class="text-light fs-5 mb-0">02-642-5425</p>
                </div>
            </div> -->
     </div>
 </nav>
 <!-- Navbar End -->