 <!-- Navbar Start -->
 <!-- Footer Start -->
<?php
// ✅ ดึงข้อมูลสินค้าทั้งหมด (ใช้ใน nav.php)
$sqlAllProducts = "SELECT id_product, name_product FROM product";
$stmtAllProducts = $pdo->query($sqlAllProducts);
$all_products = $stmtAllProducts->fetchAll(PDO::FETCH_ASSOC);
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
            <a href="index.php" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">หน้าแรก</a>
            <a href="index.php#about" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php#about' ? 'active' : ''; ?>">เกี่ยวกับเรา</a>
            <a href="index.php#Testimonial" class="nav-item nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php#Testimonial' ? 'active' : ''; ?>">รีวิว</a>
             <div class="nav-item dropdown">
                 <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">ผลิตภัณฑ์</a>
                 <div class="dropdown-menu m-0">
                 <?php foreach ($all_products as $productItem): ?>
                         <a class="dropdown-item" href="productdetails.php?id=<?= $productItem['id_product']; ?>"><?= $productItem['name_product']; ?></a>
                     <?php endforeach; ?>
                     
                 </div>
             </div>
         </div>
         
     </div>
 </nav>
