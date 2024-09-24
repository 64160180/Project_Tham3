<?php
// ตรวจสอบว่าเซสชันยังไม่ได้เริ่มต้น
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/condb.php';

// คิวรีประวัติการสั่งซื้อ
$query = $condb->prepare("SELECT * FROM tbl_order ORDER BY date_out DESC");
$query->execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการนำออกสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ประวัติการนำออกสินค้า</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="table-info">
                                        <th width="15%">วันที่</th>
                                        <th width="35%">ชื่อสินค้า</th>
                                        <th width="10%">ราคาทุน</th>
                                        <th width="10%">ราคาขาย</th>
                                        <th width="10%">จำนวน</th>
                                        <th width="20%">ราคารวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($orders as $order):
                                        $totalPrice = $order['sell_price'] * $order['quantity'];
                                    ?>
                                    <tr>
                                        <td><?= date('Y-m-d H:i:s', strtotime($order['date_out'])); ?></td>
                                        <td><?= $order['product_name']; ?></td>
                                        <td><?= number_format($order['cost_price'], 2); ?> บาท</td>
                                        <td><?= number_format($order['sell_price'], 2); ?> บาท</td>
                                        <td><?= $order['quantity']; ?></td>
                                        <td><?= number_format($totalPrice, 2); ?> บาท</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
