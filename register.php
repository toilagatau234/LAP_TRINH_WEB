<?php

include 'config.php';

if (isset($_POST['submit'])) {

   // buộc người đăng ký mới phải có vai trò 'người dùng'
   $user_type = 'user';
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   // băm mật khẩu trước khi thoát
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $pass = mysqli_real_escape_string($conn, $pass);
   $cpass = mysqli_real_escape_string($conn, $cpass);

   // kiểm tra xem email đã tồn tại chưa (ngăn chặn các tài khoản trùng lặp cho cùng một email)
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {
      $message[] = 'user already exist!'; // người dùng đã tồn tại!
   } else {
      if ($pass != $cpass) { // xác nhận mật khẩu không khớp
         $message[] = 'xác nhận mật khẩu không khớp!';
      } else {
         $expected_id = 1; // tìm id người dùng trống đầu tiên
         $ids_result = mysqli_query($conn, "SELECT id FROM `users` ORDER BY id ASC") or die('query failed');
         while ($row = mysqli_fetch_assoc($ids_result)) { // duyệt qua các id hiện có
            $current_id = (int)$row['id']; // chuyển đổi id hiện tại sang số nguyên
            if ($current_id == $expected_id) { // id hiện tại khớp với id dự kiến
               $expected_id++; // tăng id dự kiến lên để kiểm tra tiếp
            } elseif ($current_id > $expected_id) { // khoảng trống được tìm thấy
               break;
            }
         }

         $new_id = (int)$expected_id;

         $query = "INSERT INTO `users`(id, name, email, password, user_type) VALUES($new_id, '$name', '$email', '$pass', '$user_type')";
         mysqli_query($conn, $query) or die('query failed: ' . mysqli_error($conn));
         $message[] = 'registered successfully!';
         header('location:login.php');
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
   <meta name="description" content="Knowledge space for nerds. Search online books by subject and add them to your favorite cart">
   <meta name="keywords" content="php, sql, mysql, html, css, javascript, book">
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/register.css">

</head>

<body>



   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
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