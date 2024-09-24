<?php 
// คิวรี่รายละเอียดสินค้า
$stmtProductDetail = $condb->prepare("
SELECT p.*, t.type_name
FROM tbl_product as p
INNER JOIN tbl_type as t ON p.ref_type_id = t.type_id
WHERE p.id=:id");
// bindParam
$stmtProductDetail->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmtProductDetail->execute();
$rowProduct = $stmtProductDetail->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบผลลัพธ์จากการคิวรี่
if($stmtProductDetail->rowCount() == 0){
    echo '<script>
        setTimeout(function() {
        swal({
            title: "เกิดข้อผิดพลาด",
            type: "error"
        }, function() {
            window.location = "product.php"; // หน้าที่ต้องการให้กระโดดไป
        });
        }, 1000);
    </script>';
    exit;
}

// คิวรี่ข้อมูลหมวดหมู่สินค้า
$queryType = $condb->prepare("SELECT * FROM tbl_type");
$queryType->execute();
$rsType = $queryType->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> แก้ไขข้อมูลสินค้า </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="card-body">

                                    <!-- หมวดหมู่สินค้า -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">หมวดหมู่สินค้า</label>
                                        <div class="col-sm-3">
                                            <select name="ref_type_id" class="form-control" required>
                                                <option value="<?php echo $rowProduct['ref_type_id']; ?>">-- <?php echo $rowProduct['type_name']; ?> --</option>
                                                <option disabled>-- เลือกข้อมูลใหม่ --</option>
                                                <?php foreach($rsType as $row){ ?>
                                                <option value="<?php echo $row['type_id']; ?>">-- <?php echo $row['type_name']; ?> --</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ชื่อสินค้า -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อสินค้า</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="product_name" class="form-control" required placeholder="ชื่อสินค้า" value="<?php echo $rowProduct['product_name']?>">
                                        </div>
                                    </div>

                                    <!-- จำนวนสินค้า -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">จำนวนสินค้า</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="product_qty" class="form-control" min="0" max="999" value="<?php echo $rowProduct['product_qty']?>">
                                        </div>
                                    </div>

                                    <!-- ราคาทุน -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">ราคาทุน</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="cost_price" class="form-control" min="0" max="99999" value="<?php echo $rowProduct['cost_price']?>">
                                        </div>
                                    </div>

                                    <!-- ราคาสินค้า -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">ราคาสินค้า</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="product_price" class="form-control" min="0" max="99999" value="<?php echo $rowProduct['product_price']?>">
                                        </div>
                                    </div>

                                    <!-- ภาพสินค้า -->
                                    <div class="form-group row">
                                        <label class="col-sm-2">ภาพสินค้า</label>
                                        <div class="col-sm-4">
                                            ภาพเก่า <br>
                                            <img src="../assets/product_img/<?php echo $rowProduct['product_image']?>" width="200px">
                                            <br> <br>
                                            เลือกภาพใหม่ 
                                            <br>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="product_image" class="custom-file-input" id="exampleInputFile" accept="image/*">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ปุ่มบันทึกและยกเลิก -->
                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?php echo $rowProduct['id']?>">
                                            <input type="hidden" name="oldImg" value="<?php echo $rowProduct['product_image']?>">
                                            <button type="submit" class="btn btn-primary ">บันทึก</button>
                                            <a href="product.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php 
// เช็ค input ที่ส่งมาจากฟอร์ม
if(isset($_POST['product_name']) && isset($_POST['ref_type_id']) && isset($_POST['product_price']) && isset($_POST['cost_price'])) {

    //trigger exception in a "try" block
    try {

    // ประกาศตัวแปรรับค่าจากฟอร์ม
    $ref_type_id = $_POST['ref_type_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_qty = $_POST['product_qty'];
    $cost_price = $_POST['cost_price']; 
    $id = $_POST['id'];
    $upload = $_FILES['product_image']['name'];

    // ตรวจสอบการอัพโหลดไฟล์
    if($upload == '') {
        // ไม่มีการอัพโหลดไฟล์
        $stmtUpdateProduct = $condb->prepare("UPDATE tbl_product SET
            ref_type_id=:ref_type_id,
            product_name=:product_name,
            product_qty=:product_qty,
            product_price=:product_price,
            cost_price=:cost_price
            WHERE id=:id
        ");
        // bindParam
        $stmtUpdateProduct->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUpdateProduct->bindParam(':ref_type_id', $ref_type_id, PDO::PARAM_INT);
        $stmtUpdateProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        $stmtUpdateProduct->bindParam(':product_qty', $product_qty, PDO::PARAM_INT);
        $stmtUpdateProduct->bindParam(':product_price', $product_price, PDO::PARAM_INT);
        $stmtUpdateProduct->bindParam(':cost_price', $cost_price, PDO::PARAM_INT); 
        $result = $stmtUpdateProduct->execute();
        if($result){
            echo '<script>
                setTimeout(function() {
                swal({
                    title: "บันทึกข้อมูลสำเร็จ",
                    type: "success"
                }, function() {
                    window.location = "product.php";
                });
                }, 1000);
            </script>';
        } //if
        
    } else {
        // มีการอัพโหลดไฟล์
        $date1 = date("Ymd_His");
        $numrand = (mt_rand());
        $typefile = strrchr($_FILES['product_image']['name'], ".");
        if($typefile == '.jpg' || $typefile == '.jpeg' || $typefile == '.png') {
            // ลบภาพเก่า
            unlink('../assets/product_img/'.$_POST['oldImg']);
            // โฟลเดอร์ที่เก็บไฟล์
            $path="../assets/product_img/";
            $newname = $numrand . $date1 . $typefile;
            move_uploaded_file($_FILES['product_image']['tmp_name'], $path.$newname);
            
            // อัปเดตข้อมูลสินค้าพร้อมภาพใหม่
            $stmtUpdateProduct = $condb->prepare("UPDATE tbl_product SET
                ref_type_id=:ref_type_id,
                product_name=:product_name,
                product_qty=:product_qty,
                product_price=:product_price,
                cost_price=:cost_price,
                product_image=:product_image
                WHERE id=:id
            ");
            // bindParam
            $stmtUpdateProduct->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdateProduct->bindParam(':ref_type_id', $ref_type_id, PDO::PARAM_INT);
            $stmtUpdateProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $stmtUpdateProduct->bindParam(':product_qty', $product_qty, PDO::PARAM_INT);
            $stmtUpdateProduct->bindParam(':product_price', $product_price, PDO::PARAM_INT);
            $stmtUpdateProduct->bindParam(':cost_price', $cost_price, PDO::PARAM_INT); // bindParam ราคาทุน
            $stmtUpdateProduct->bindParam(':product_image', $newname, PDO::PARAM_STR);
            $result = $stmtUpdateProduct->execute();
            if($result){
                echo '<script>
                    setTimeout(function() {
                    swal({
                        title: "บันทึกข้อมูลสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "product.php";
                    });
                    }, 1000);
                </script>';
            } //if
            
        } else {
            echo '<script>
                setTimeout(function() {
                swal({
                    title: "รูปแบบไฟล์ไม่ถูกต้อง",
                    type: "error"
                }, function() {
                    window.location = "product.php";
                });
                }, 1000);
            </script>';
        } //eles upload file
         } //eles not upload file
        } //try
        //catch exception
        catch(Exception $e) {
            //echo 'Message: ' .$e->getMessage();
            echo '<script>
                setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    type: "error"
                }, function() {
                    window.location = "product.php"; //หน้าที่ต้องการให้กระโดดไป
                });
                }, 1000);
            </script>';
        } //catch
    } //isset
?>
