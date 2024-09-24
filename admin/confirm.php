<?php
session_start();
require_once '../config/condb.php';

// แสดงข้อผิดพลาด (สำหรับการพัฒนา)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// บันทึกข้อมูลคำสั่งซื้อ
foreach ($products as $product) {
    $quantity = $cartItems[$product['id']];
    
    // เพิ่มข้อมูลลงใน tbl_order
    $insertOrder = $condb->prepare("INSERT INTO tbl_order (product_id, product_name, cost_price, sell_price, quantity) VALUES (:product_id, :product_name, :cost_price, :sell_price, :quantity)");
    $insertOrder->bindParam(':product_id', $product['id']);
    $insertOrder->bindParam(':product_name', $product['product_name']);
    $insertOrder->bindParam(':cost_price', $product['cost_price']);
    $insertOrder->bindParam(':sell_price', $product['product_price']);
    $insertOrder->bindParam(':quantity', $quantity);
    $insertOrder->execute();
    
    // ลดจำนวนสินค้าลงใน tbl_product
    $newQuantity = $product['product_qty'] - $quantity;
    $updateProduct = $condb->prepare("UPDATE tbl_product SET product_qty = :newQuantity WHERE id = :product_id");
    $updateProduct->bindParam(':newQuantity', $newQuantity);
    $updateProduct->bindParam(':product_id', $product['id']);
    $updateProduct->execute();
}

// เคลียร์รถเข็น
unset($_SESSION['cart']);

// แจ้งเตือนว่าการสั่งซื้อสำเร็จ
echo "<div class='alert alert-success' role='alert'>
        <h4 class='alert-heading'>การสั่งซื้อสำเร็จ!</h4>
        <p>สินค้าของคุณถูกนำออกเรียบร้อยแล้ว.</p>
        <p><a href='product.php' class='btn btn-primary'>กลับไปยังหน้าสินค้า</a></p>
      </div>";
?>
