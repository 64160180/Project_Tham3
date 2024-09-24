<?php
// จำนวนสินค้าที่เหลือน้อยกว่า 10 ชิ้น
$stmtCountLowStock = $condb->prepare("SELECT product_name FROM tbl_product WHERE product_qty < 10");
$stmtCountLowStock->execute();
$lowStockItems = $stmtCountLowStock->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../admin/css/notification.css">
</head>
<body>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="main.php" class="nav-link">หน้าแรก</a>
        </li>
        
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notification Button -->
        <li class="nav-item position-relative">
            <a class="nav-link" data-toggle="modal" data-target="#notificationModal">
                <i class="fas fa-bell fa-lg"></i>
                <?php if (count($lowStockItems) > 0): ?>
                    <span class="notification-badge"><?= count($lowStockItems); ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="logout.php" class="btn btn-outline-primary me-4" style="margin-left: 15px;">Logout</a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">การแจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    <?php foreach ($lowStockItems as $item): ?>
                        <li>ชื่อสินค้าที่เหลือน้อย: <?= htmlspecialchars($item['product_name']); ?> </li> 
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
