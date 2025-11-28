<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) { header('location:login.php'); }

$db = new Database();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Orders</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="./styles/customers/order.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>
<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Đơn hàng của bạn</h3>
      <p> <a href="home.php">Trang chủ</a> / Đơn đặt hàng </p>
   </div>

   <section class="order-container">
      <div class="order-title">
         <h1>Lịch sử đặt hàng</h1>
      </div>
      <table cellspacing="0">
         <tr>
            <td>Ngày đặt</td>
            <td>Tên</td>
            <td>SĐT</td>
            <td>Email</td>
            <td>Địa chỉ</td>
            <td>Thanh toán</td>
            <td>Sản phẩm</td>
            <td>Tổng tiền</td>
            <td>Trạng thái</td>
         </tr>
         <?php
         // --- LOGIC PHÂN TRANG ---
         $limit = 10; 
         $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;// Lấy trang hiện tại từ URL hoặc mặc định là 1
         if ($current_page < 1) $current_page = 1;
         $offset = ($current_page - 1) * $limit;

         // Đếm tổng (Chỉ đếm của user hiện tại)
         $db->query("SELECT COUNT(*) as total FROM `orders` WHERE user_id = :uid");
         $db->bind(':uid', $user_id);
         $row_count = $db->single();
         $total_records = $row_count['total'];
         $total_pages = ceil($total_records / $limit);

         // Lấy dữ liệu
         $db->query("SELECT * FROM `orders` WHERE user_id = :uid ORDER BY id DESC LIMIT $offset, $limit");
         $db->bind(':uid', $user_id);
         $order_query = $db->resultSet();

         if (count($order_query) > 0) {// Kiểm tra có đơn hàng không
            foreach ($order_query as $fetch_orders) {// Lặp qua từng đơn hàng
         ?>
               <tr>
                  <td><?php echo $fetch_orders['placed_on']; ?></td>
                  <td><?php echo $fetch_orders['name']; ?></td>
                  <td><?php echo $fetch_orders['number']; ?></td>
                  <td><?php echo $fetch_orders['email']; ?></td>
                  <td><?php echo $fetch_orders['address']; ?></td>
                  <td><?php echo $fetch_orders['method']; ?></td>
                  <td><?php echo $fetch_orders['total_products']; ?></td>
                  <td>$<?php echo $fetch_orders['total_price']; ?></td>
                  <td>
                     <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>;">
                        <?php echo $fetch_orders['payment_status']; ?>
                     </span>
                  </td>
               </tr>
         <?php
            }
         } else {
            echo '<tr><td colspan="9" class="empty" style="text-align:center; padding: 2rem;">Chưa có đơn hàng nào được đặt!</td></tr>';
         }
         ?>
      </table>
      
      <?php 
         $base_url = 'orders.php';
         include 'components/pagination.php'; 
      ?>

      <div class="order-total">
         <h3>Tổng số đơn: <?php echo $total_records; ?></h3>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>

</body>
</html>