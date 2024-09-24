<?php

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['username']) && isset($_POST['password'])) {
    // เรียกใช้ SweetAlert สำหรับการแจ้งเตือน
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    // เชื่อมต่อฐานข้อมูล
    require_once '../config/condb.php';

    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // เก็บรหัสผ่านในรูปแบบ sha1 

    // ตรวจสอบ username และ password รวมถึง role
    $stmt = $condb->prepare("SELECT id, name, surname, role FROM tbl_member WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    // ถ้า username & password ถูกต้อง
    if ($stmt->rowCount() == 1) {
        // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        // สร้าง session
        session_start(); // เริ่มต้น session
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        $_SESSION['role'] = $row['role']; // กำหนด role ใน session

        // ตรวจสอบ role เพื่อกระโดดไปยังหน้าที่ถูกต้อง
        if ($_SESSION['role'] == 'admin') {
            header('Location: main.php'); // ถ้าเป็น admin ให้ไปที่หน้า admin
        } else {
            header('Location: main.php'); // ถ้าเป็น user ปกติ ให้ไปที่หน้า user
        }
        exit(); // จบการทำงานเพื่อป้องกันการรันโค้ดที่ไม่ต้องการ
    } else { // ถ้า username หรือ password ไม่ถูกต้อง
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                    type: "warning"
                }, function() {
                    window.location = "index.php"; // หน้าที่ต้องการให้กระโดดไป
                });
            }, 1000);
        </script>';
        $condb = null; // ปิดการเชื่อมต่อฐานข้อมูล
    }
}
?>
