<?php
// Gọi file cấu hình và class Database mới
require_once 'includes/db.php';

session_start();

// Khởi tạo đối tượng Database
$db = new Database();

if (isset($_POST['submit'])) {

   $email = $_POST['email'];
   // Mã hóa mật khẩu MD5 để so sánh
   $pass = md5($_POST['password']);

   // Chuẩn bị câu truy vấn (Sử dụng :email, :password để bảo mật thay cho mysqli_real_escape_string)
   $db->query("SELECT * FROM `users` WHERE email = :email AND password = :password");
   
   // Gán giá trị vào tham số
   $db->bind(':email', $email);
   $db->bind(':password', $pass);
   
   // Lấy 1 dòng kết quả
   $row = $db->single();

   // Kiểm tra số dòng trả về (thay cho mysqli_num_rows)
   if ($db->rowCount() > 0) {

      // Đăng nhập thành công, lưu session
      if ($row['user_type'] == 'admin') {
         $_SESSION['admin_name'] = $row['name'];// Lưu tên admin vào session
         $_SESSION['admin_email'] = $row['email'];// Lưu email admin vào session
         $_SESSION['admin_id'] = $row['id'];// Lưu id admin vào session
         header('location:admin_page.php');
      } elseif ($row['user_type'] == 'user') {
         $_SESSION['user_name'] = $row['name'];// Lưu tên user vào session
         $_SESSION['user_email'] = $row['email'];// Lưu email user vào session
         $_SESSION['user_id'] = $row['id'];// Lưu id user vào session
         header('location:home.php');
      }
   } else {
      $message[] = 'Email hoặc mật khẩu không chính xác!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Login</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="./styles/customers/login.css">
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
      <form class="login_form" action="" method="post">
         <div class="form-inner">
            <h2>Đăng nhập ngay</h2>
            <div class="input-group">
               <div class="icon">
                  <img src="./public/form/user.svg" alt="user">
               </div>
               <input type="email" name="email" placeholder="nhập email của bạn" required class="box">
            </div>
            <div class="input-group">
               <div class="icon">
                  <img src="./public/form/finger_print.svg" alt="finger_print">
               </div>
               <input type="password" name="password" placeholder="nhập mật khẩu của bạn" required class="box">
            </div>
            <div class="btn-group">
               <input type="submit" name="submit" value="login now" class="btn">
            </div>
            <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
         </div>
      </form>
   </div>

</body>
</html>