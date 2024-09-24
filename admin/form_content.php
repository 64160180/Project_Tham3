<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>จัดการข้อมูลสินค้า</h1>
          </div>
          <div class="col-sm-6">
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            
            <!-- /.card-header -->
            <div class="card-body">
              <textarea id="summernote">
                Place <em>some</em> <u>text</u> <strong>here</strong>
              </textarea>
              <!-- ส่วนเสริม -->
              <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                <div class="form-group">
                  <label for="exampleInputEmail1">รหัสสินค้า</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" placeholder="รหัสสินค้า">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">ชื่อสินค้า</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="ชื่อสินค้า">
                </div>

              
                <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="exampleInputFile">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                    <div class="input-group-append">
                      <span class="input-group-text">Upload</span>
                    </div>
                  </div>
                </div>
                <button class="btn btn-secondary" onclick="stepper.previous()">ย้อนกลับ</button>
                <button type="submit" class="btn btn-primary">บันทึก</button>
              </div>
            </div>
          </div>
        </div>
        <!-- ส่วนเสริม -->
         
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
