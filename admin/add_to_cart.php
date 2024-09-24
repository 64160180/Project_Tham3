<?php
session_start();

// กำหนดรถเข็น หากยังไม่ได้กำหนด
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// รับค่า ID ของสินค้าจาก URL
$product_id = $_GET['id'] ?? null;

// รับค่า quantity จากฟอร์ม
$quantity = $_GET['quantity'] ?? 1; // กำหนดค่าเริ่มต้นเป็น 1 ถ้าไม่ได้ส่งค่า quantity มา

if($product_id) {
    // ตรวจสอบว่าสินค้าอยู่ในรถเข็นแล้วหรือยัง
    if(isset($_SESSION['cart'][$product_id])) {
        // หากสินค้าอยู่ในรถเข็นแล้ว เพิ่มจำนวนสินค้าตามที่ระบุ
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // หากสินค้ายังไม่อยู่ในรถเข็น ให้เพิ่มสินค้าโดยกำหนดจำนวนตามที่ระบุ
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// ย้อนกลับไปยังหน้าก่อนหน้า (หรือหน้ารถเข็น)
header("Location: cart.php");
exit;
