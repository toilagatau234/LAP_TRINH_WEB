<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){ header('location:login.php'); }

$db = new Database();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin | Dashboard</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/home.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <h1 class="title">bảng điều khiển</h1>
   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;// Tổng số tiền đang chờ xử lý
            $db->query("SELECT total_price FROM `orders` WHERE payment_status = 'pending'");
            $pendings = $db->resultSet();// Lấy tất cả đơn hàng đang chờ xử lý
            foreach($pendings as $fetch_pendings){
               $total_pendings += $fetch_pendings['total_price'];// Cộng dồn tổng tiền
            }
         ?>
         <h3>$<?php echo $total_pendings; ?></h3>
         <p>tổng số tiền đang chờ xử lý</p>
      </div>

      <div class="box">
         <?php
            $total_completed = 0;// Tổng số tiền đã hoàn tất
            $db->query("SELECT total_price FROM `orders` WHERE payment_status = 'completed'");// Lấy tất cả đơn hàng đã hoàn tất
            $completed = $db->resultSet();
            foreach($completed as $fetch_completed){
               $total_completed += $fetch_completed['total_price'];// Cộng dồn tổng tiền
            }
         ?>
         <h3>$<?php echo $total_completed; ?></h3>
         <p>thanh toán đã hoàn tất</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `orders`");// Lấy tất cả đơn hàng
            $db->execute();// Thực thi câu truy vấn
            $number_of_orders = $db->rowCount();// Đếm số đơn hàng
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>đơn hàng đã đặt</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `products`");// Lấy tất cả sản phẩm
            $db->execute();// Thực thi câu truy vấn
            $number_of_products = $db->rowCount();// Đếm số sản phẩm
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>sản phẩm đã thêm</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `users` WHERE user_type = 'user'");// Lấy tất cả người dùng bình thường
            $db->execute();// Thực thi câu truy vấn
            $number_of_users = $db->rowCount();// Đếm số người dùng bình thường
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>người dùng bình thường</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `users` WHERE user_type = 'admin'");// Lấy tất cả người dùng quản trị
            $db->execute();// Thực thi câu truy vấn
            $number_of_admins = $db->rowCount();// Đếm số người dùng quản trị
         ?>
         <h3><?php echo $number_of_admins; ?></h3>
         <p>người dùng quản trị</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `users`");   // Lấy tất cả tài khoản
            $db->execute();// Thực thi câu truy vấn
            $number_of_account = $db->rowCount();// Đếm số tài khoản
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>tổng số tài khoản</p>
      </div>

      <div class="box">
         <?php 
            $db->query("SELECT * FROM `message`");// Lấy tất cả tin nhắn
            $db->execute();// Thực thi câu truy vấn
            $number_of_messages = $db->rowCount();// Đếm số tin nhắn
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>tin nhắn mới</p>
      </div>
   </div>
</section>
<script src="js/admin_script.js"></script>
</body>
</html>