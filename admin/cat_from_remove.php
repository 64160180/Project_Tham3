<?php
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    if (isset($_GET['action']) && $_GET['action'] === 'decrease') {
        // ลดจำนวนสินค้าทีละ 1
        if ($_SESSION['cart'][$productId] > 1) {
            $_SESSION['cart'][$productId]--;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    } elseif (isset($_GET['action']) && $_GET['action'] === 'remove') {
        // ลบสินค้าทั้งหมดในรายการ
        unset($_SESSION['cart'][$productId]);
    }
}

header("Location: cart.php");
exit();
