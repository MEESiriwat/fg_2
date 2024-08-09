<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>Member</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-8"> <br> 
          <h4>ระบบสมัครสมาชิก</h4>
          <form action="" method="post">
            <div class="mb-2">
                <div class="col-sm-9">
                <input type="text" name="id_card" class="form-control" required minlength="3" placeholder="ID">
              </div>
              </div>
              <div class="mb-2">
                <div class="col-sm-9">
                  <input type="text" name="username" class="form-control" required minlength="3" placeholder="Username">
                </div>
                </div>
                <div class="mb-2">
                <div class="col-sm-9">
                  <input type="text" name="fname" class="form-control" required minlength="3" placeholder="ชื่อ">
                </div>
                </div>
                <div class="mb-3">
                <div class="col-sm-9">
                  <input type="text" name="lname" class="form-control" required minlength="3" placeholder="นามสกุล">
                </div>
                </div>
                <div class="mb-4">
                <div class="col-sm-9">
                  <input type="password" name="password" class="form-control" required minlength="3" placeholder="รหัสผ่าน">
                </div>
                </div>
                <div class="d-grid gap-2 col-sm-9 mb-3">
                <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </body>
    </html>  


    <?php

  //print_r($_POST); //ตรวจสอบมี input อะไรบ้าง และส่งอะไรมาบ้าง 
 //ถ้ามีค่าส่งมาจากฟอร์ม
    if(isset($_POST['id_card']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['username']) && isset($_POST['password']) ){
    // sweet alert 
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    //ไฟล์เชื่อมต่อฐานข้อมูล
    require_once 'connectdb.php';
    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $id_card = $_POST['id_card'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = sha1($_POST['password']); //เก็บรหัสผ่านในรูปแบบ sha1 

    //check duplicat
      $stmt = $conn->prepare("SELECT username FROM register WHERE username = :username");
      //$stmt->bindParam(':username', $username , PDO::PARAM_STR);
      $stmt->execute(array(':username' => $username));
      //ถ้า username ซ้ำ ให้เด้งกลับไปหน้าสมัครสมาชิก ปล.ข้อความใน sweetalert ปรับแต่งได้ตามความเหมาะสม
      if($stmt->rowCount() > 0){
          echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "Username ซ้ำ !! ",  
                            text: "กรุณาสมัครใหม่อีกครั้ง",
                            type: "warning"
                        }, function() {
                            window.location = "formRegister.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1000);
                </script>';
      }else{ //ถ้า username ไม่ซ้ำ เก็บข้อมูลลงตาราง
              //sql insert
              $stmt = $conn->prepare("INSERT INTO register (id_card, fname, lname, username, password)
              VALUES (:id_card, :fname, :lname, :username, :password)");
              $stmt->bindParam(':id_card', $id_card, PDO::PARAM_STR);
              $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
              $stmt->bindParam(':lname', $lname , PDO::PARAM_STR);
              $stmt->bindParam(':username', $username , PDO::PARAM_STR);
              $stmt->bindParam(':password', $password , PDO::PARAM_STR);
              $result = $stmt->execute();
              if($result){
                  echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "สมัครสมาชิกสำเร็จ",
                            text: "กรุณารอระบบ Login ใน Workshop ต่อไป",
                            type: "success"
                        }, function() {
                            window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1000);
                  </script>';
              }else{
                 echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                            type: "error"
                        }, function() {
                            window.location = "formRegister.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1000);
                  </script>';
              }
              $conn = null; //close connect db
        } //else chk dup
    } //isset 
    //devbanban.com
    ?>