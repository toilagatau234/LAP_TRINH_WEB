<?php
// 1. Gọi file db.php chứa class Database
require_once 'includes/db.php';

session_start();

// 2. Khởi tạo đối tượng Database
$db = new Database();

if (isset($_POST['submit'])) {// Kiểm tra nếu form đã được gửi

   // Lấy dữ liệu từ form
   $name = $_POST['name'];// Lấy tên người dùng
   $email = $_POST['email'];// Lấy email người dùng
   // Mã hóa mật khẩu
   $pass = md5($_POST['password']);// Mã hóa mật khẩu
   $cpass = md5($_POST['cpassword']);// Mã hóa mật khẩu xác nhận
   $user_type = 'user';

   // 3. Kiểm tra email đã tồn tại chưa (Dùng bind param để bảo mật)
   $db->query("SELECT * FROM `users` WHERE email = :email");// Chuẩn bị câu truy vấn kiểm tra email
   $db->bind(':email', $email);// Bind email người dùng
   $db->execute();

   // 4. Kiểm tra số dòng trả về
   if ($db->rowCount() > 0) {// Nếu có người dùng với email này
      $message[] = 'Người dùng đã tồn tại!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } else {
         // Tìm ID trống tiếp theo (Logic giữ nguyên từ code cũ của bạn)
         $expected_id = 1;// Bắt đầu từ ID 1
         $db->query("SELECT id FROM `users` ORDER BY id ASC");// Chuẩn bị câu truy vấn lấy danh sách ID người dùng
         $ids_result = $db->resultSet();// Lấy kết quả danh sách ID

         foreach($ids_result as $row) { // Lặp qua từng ID 
            $current_id = (int)$row['id'];// Chuyển ID hiện tại sang kiểu số nguyên
            if ($current_id == $expected_id) {// Nếu ID hiện tại khớp với ID mong đợi
               $expected_id++;// Tăng ID mong đợi lên 1
            } elseif ($current_id > $expected_id) {// Nếu ID hiện tại lớn hơn ID mong đợi, nghĩa là có lỗ hổng
               break;
            }
         }
         $new_id = (int)$expected_id;

         // 5. Thêm người dùng mới vào CSDL
         $db->query("INSERT INTO `users`(id, name, email, password, user_type) VALUES(:id, :name, :email, :password, :user_type)");
         $db->bind(':id', $new_id);// Bind ID người dùng
         $db->bind(':name', $name);// Bind tên người dùng
         $db->bind(':email', $email);// Bind email người dùng
         $db->bind(':password', $pass);// Bind mật khẩu người dùng
         $db->bind(':user_type', $user_type);// Bind loại người dùng
         
         if($db->execute()){
             $message[] = 'Đăng ký thành công!';// Thông báo đăng ký thành công
             header('location:login.php');
         } else {
             $message[] = 'Đăng ký thất bại!';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Register</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/register.css">

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>

   <div class="form-container">
      <form class="register_form" action="" method="post">
         <div class="form-inner">
            <h2>Đăng ký ngay</h2>
            <div class="input-group">
               <div class="icon">
                  <img src="./public/form/user.svg" alt="user">
               </div>
               <input type="text" name="name" placeholder="nhập tên của bạn" required class="box">
            </div>
            <div class="input-group">
               <div class="icon">
                  <i class="fa-regular fa-envelope"></i>
               </div>
               <input type="email" name="email" placeholder="nhập email của bạn" required class="box">
            </div>
            <div class="input-group">
               <input type="password" name="password" placeholder="nhập mật khẩu của bạn" required class="box">
            </div>
            <div class="input-group">
               <input type="password" name="cpassword" placeholder="xác nhận mật khẩu của bạn" required class="box">
            </div>
            <div class="btn-group">
               <input type="submit" name="submit" value="register now" class="btn">
            </div>
            <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
         </div>
      </form>
   </div>
</body>

</html>