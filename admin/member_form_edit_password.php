<?php
if(isset($_GET['id']) && $_GET['act'] == 'editPwd' ){

    // Query สำหรับแสดงข้อมูลสมาชิกโดยแสดงแค่ 1 รายการ
    $stmtMemberDetail = $condb->prepare("SELECT * FROM tbl_member WHERE id=?");
    $stmtMemberDetail->execute([$_GET['id']]);
    $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

    // ถ้าคิวรี่ผิดพลาดหรือไม่มีรายการใด ๆ ให้หยุดการทำงาน
    if($stmtMemberDetail->rowCount() != 1){
        exit();
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> แก้ไขรหัสผ่าน </h1>
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
                            <!-- แบบฟอร์มสำหรับแก้ไขรหัสผ่าน -->
                            <form action="" method="post">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2">Email/Username</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="username" class="form-control"
                                                value="<?php echo $row['username'];?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ-สกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required
                                                placeholder="ชื่อ"
                                                value="<?php echo $row['title_name'].$row['name'].' '.$row['surname'];?>"
                                                disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">New Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="NewPassword" class="form-control" required
                                                placeholder="รหัสผ่านใหม่">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Confirm Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="ConfirmPassword" class="form-control" required
                                                placeholder="ยืนยันรหัสผ่าน">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                                            <button type="submit" class="btn btn-primary">แก้ไขรหัสผ่าน</button>
                                            <a href="member.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
</div>
<!-- /.col-->
</div>

<!-- ./row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php 
if(isset($_POST['id']) && isset($_POST['NewPassword']) && isset($_POST['ConfirmPassword'])){
    
    //trigger exception in a "try" block
    try {

    $id = $_POST['id'];   
    $NewPassword = $_POST['NewPassword'];  
    $ConfirmPassword = $_POST['ConfirmPassword'];
    
    // ตรวจสอบว่ารหัสผ่านใหม่และรหัสผ่านยืนยันตรงกันหรือไม่
    if($NewPassword != $ConfirmPassword){
        echo '<script>
             setTimeout(function() {
              swal({
                  title: "รหัสผ่านไม่ตรงกัน",
                  text: "กรุณากรอกรหัสผ่านใหม่อีกครั้ง",
                  type: "error"
              }, function() {
                  window.location = "member.php?id='.$id.'&act=editPwd"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1000);
        </script>';
    } else {
        // เข้ารหัสรหัสผ่านด้วย sha1
        $password = sha1($NewPassword);

        // SQL สำหรับอัปเดตรหัสผ่านในฐานข้อมูล
        $stmtUpdate = $condb->prepare("UPDATE tbl_member SET password=:password WHERE id=:id");
        
        // bindParam สำหรับเชื่อมต่อค่าไปที่ query
        $stmtUpdate->bindParam(':password', $password , PDO::PARAM_STR);
        $stmtUpdate->bindParam(':id', $id , PDO::PARAM_INT);
        
        $result = $stmtUpdate->execute();

        $condb = null; // ปิดการเชื่อมต่อฐานข้อมูล

        if($result){
            echo '<script>
                 setTimeout(function() {
                  swal({
                      title: "แก้ไขรหัสผ่านสำเร็จ",
                      type: "success"
                  }, function() {
                      window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
                  });
                }, 1000);
            </script>';
        } 
    }  

} //try
//catch exception
catch(Exception $e) {
    //echo 'Message: ' .$e->getMessage();
    echo '<script>
         setTimeout(function() {
          swal({
              title: "เกิดข้อผิดพลาด",
              text: "กรุณาติดต่อผู้ดูแลระบบ",
              type: "error"
          }, function() {
              window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
          });
        }, 1000);
    </script>';
  } //catch
} 
?>