<?php
session_start();
require_once '../config/condb.php';

// แสดงข้อผิดพลาด (สำหรับการพัฒนา)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// ตรวจสอบว่ามีข้อมูลรถเข็นหรือไม่
if (!isset($_POST['cart'])) {
    echo "<div class='alert alert-warning' role='alert'>
            <h4 class='alert-heading'>ไม่มีข้อมูลการสั่งซื้อ</h4>
            <p><a href='cart.php' class='btn btn-primary'>กลับไปยังรถเข็น</a></p>
          </div>";
    exit();
}

// แปลงข้อมูลรถเข็นกลับมาเป็นอาร์เรย์
$cartItems = unserialize(htmlspecialchars_decode($_POST['cart']));

// คิวรีข้อมูลสินค้าจากฐานข้อมูล
$productIds = implode(',', array_keys($cartItems));
$query = $condb->prepare("SELECT * FROM tbl_product WHERE id IN ($productIds)");
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าชำระเงิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/css/payment.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">บิลรายละเอียดการสั่งซื้อ</h2>
        <table class="table table-bordered">
            <thead>
                <tr class="table-info">
                    <th>ภาพสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคาทุน</th>
                    <th>ราคาขาย</th>
                    <th>ราคารวม</th>
                    <th>กำไร</th>
                    <th>จำนวน</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalCost = 0; // กำหนดยอดรวมราคาทุนเริ่มต้นเป็น 0
                $totalPriceAll = 0; // กำหนดยอดรวมราคารวมเริ่มต้นเป็น 0
                
                foreach ($products as $product): 
                    $quantity = $cartItems[$product['id']];
                    $totalPrice = $product['product_price'] * $quantity; // คำนวณราคารวม
                    $costPriceTotal = $product['cost_price'] * $quantity; // คำนวณราคาทุนรวม
                    $profit = $totalPrice - $costPriceTotal; // คำนวณกำไร
                    
                    // เพิ่มยอดรวมราคาทุนและราคารวม
                    $totalCost += $costPriceTotal;
                    $totalPriceAll += $totalPrice;
                ?>
                <tr>
                    <td><img src="../assets/product_img/<?= $product['product_image']; ?>" class="img-thumbnail" width="70px"></td>
                    <td><?= $product['product_name']; ?></td>
                    <td><?= number_format($costPriceTotal, 2); ?> บาท</td>
                    <td><?= number_format($product['product_price'], 2); ?> บาท</td>
                    <td><?= number_format($totalPrice, 2); ?> บาท</td>
                    <td><?= number_format($profit, 2); ?> บาท</td>
                    <td><?= $quantity; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h3>ยอดรวมทั้งหมด: <?= number_format($totalPriceAll, 2); ?> บาท</h3>
            <h3>ยอดรวมราคาทุน: <?= number_format($totalCost, 2); ?> บาท</h3>
            <h3>กำไรทั้งหมด: <?= number_format($totalPriceAll - $totalCost, 2); ?> บาท</h3>
        </div>
        <div class="text-center">
            <a href="cart.php" class="btn btn-primary btn-custom">กลับไปยังรถเข็น</a>
            <form action="confirm.php" method="post" class="d-inline">
                <input type="hidden" name="cart" value="<?= htmlspecialchars(serialize($cartItems)); ?>">
                <button type="submit" class="btn btn-success btn-custom">ยืนยันการสั่งซื้อ</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
