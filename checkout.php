<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['order_btn'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $message[] = 'your cart is empty';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'order already placed!';
      } else {
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>Bookept | Checkout</title>
   <meta name="description" content="Knowledge space for nerds. Search online books by subject and add them to your favorite cart">
   <meta name="keywords" content="php, sql, mysql, html, css, javascript, book">
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="./styles/customers/checkout.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>thanh toán</h3>
      <p> <a href="home.php">Trang chủ</a> / thanh toán </p>
   </div>

   <section class="checkout-container">
      <form action="" method="post">
         <h3><i class="fa-solid fa-folder-open"></i> đơn hàng của bạn</h3>
         <div class="flex">
            <div class="inputBox">
               <span><i class="fa-solid fa-signature"></i> tên của bạn :</span>
               <input type="text" name="name" required placeholder="tên của bạn">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-hashtag"></i> số điện thoại của bạn :</span>
               <input type="number" name="number" required placeholder="số điện thoại của bạn">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-at"></i> email của bạn :</span>
               <input type="email" name="email" required placeholder="email của bạn">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-money-check-dollar"></i> phương thức thanh toán :</span>
               <select name="method">
                  <option value="cash on delivery">thanh toán khi nhận hàng</option>
                  <option value="credit card">thẻ tín dụng</option>
                  <option value="paypal">paypal</option>
                  <option value="momo">momo</option>
                  <option value="visa debit">visa debit</option>
               </select>
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-house"></i> số nhà :</span>
               <input type="number" min="0" name="flat" required placeholder="số nhà.">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-location-dot"></i> đường :</span>
               <input type="text" name="street" required placeholder="e.g. tên đường">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-city"></i> thành phố :</span>
               <input type="text" name="city" required placeholder="e.g. Cao Lãnh">
            </div>
            <div class="inputBox">
               <span><i class="fa-brands fa-squarespace"></i> tỉnh :</span>
               <input type="text" name="state" required placeholder="e.g. Đồng Tháp">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-earth-americas"></i> quốc gia :</span>
               <input type="text" name="country" required placeholder="e.g. Việt Nam">
            </div>
            <div class="inputBox">
               <span><i class="fa-solid fa-file-zipper"></i> mã bưu chính :</span>
               <input type="number" min="0" name="pin_code" required placeholder="e.g. 1234567">
            </div>
         </div>
         <div style="display: flex; justify-content:end">
            <input type="submit" value="🚩 đặt hàng ngay" class="btn" name="order_btn">
         </div>
      </form>

      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      ?>

      <div class="summary-order">
         <div class="summary-header">
            <h2><i class="fa-solid fa-cart-flatbed"></i> Giỏ hàng của bạn</h2>
            <h5 style="background: #888; border-radius: 50%; width:3.5rem; height:3.5rem; color:white; display:flex; justify-content:center; align-items:center"><?php echo mysqli_num_rows($select_cart) ?></h5>
         </div>
         <div class="summary-list">
            <?php
            if (mysqli_num_rows($select_cart) > 0) {
               while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                  $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                  $grand_total += $total_price;
            ?>
                  <div class="summary-item">
                     <p><?php echo $fetch_cart['name']; ?></p>
                     <p><?php echo '$' . $fetch_cart['price']; ?> &bull; <?php echo $fetch_cart['quantity']; ?></p>
                  </div>
            <?php
               }
            } else {
               echo '<p class="empty">giỏ hàng của bạn đang trống</p>';
            }
            ?>
         </div>
         <div class="summary-total">
            <p><i class="fa-solid fa-border-all"></i> tổng cộng : </p>
            <p style="color:red">$<?php echo $grand_total; ?></p>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>