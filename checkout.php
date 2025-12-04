<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) { header('location:login.php'); }

$db = new Database();

if (isset($_POST['order_btn'])) {
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   // tạo địa chỉ đầy đủ
   $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products = [];

   // lấy sản phẩm trong giỏ
   $db->query("SELECT * FROM `cart` WHERE user_id = :user_id");
   $db->bind(':user_id', $user_id);
   $cart_items = $db->resultSet();

   if (count($cart_items) > 0) {
      foreach ($cart_items as $cart_item) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
      
      $total_products = implode(', ', $cart_products);

      // kiểm tra đơn hàng trùng lặp
      $db->query("SELECT * FROM `orders` WHERE name = :name AND number = :number AND email = :email AND method = :method AND address = :address AND total_products = :total_products AND total_price = :total_price");
      $db->bind(':name', $name);
      $db->bind(':number', $number);
      $db->bind(':email', $email);
      $db->bind(':method', $method);
      $db->bind(':address', $address);
      $db->bind(':total_products', $total_products);
      $db->bind(':total_price', $cart_total);
      $db->execute();

      if ($db->rowCount() > 0) {
         $message[] = 'Đơn hàng đã được đặt trước đó!';
      } else {
         // thêm đơn hàng mới
         $db->query("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(:user_id, :name, :number, :email, :method, :address, :total_products, :total_price, :placed_on)");
         $db->bind(':user_id', $user_id);
         $db->bind(':name', $name);
         $db->bind(':number', $number);
         $db->bind(':email', $email);
         $db->bind(':method', $method);
         $db->bind(':address', $address);
         $db->bind(':total_products', $total_products);
         $db->bind(':total_price', $cart_total);
         $db->bind(':placed_on', $placed_on);
         $db->execute();

         // trừ số lượng trong kho
         foreach ($cart_items as $item) {
             $product_name_buy = $item['name'];
             $buy_qty = $item['quantity'];

             // Trừ số lượng trong bảng products
             $db->query("UPDATE `products` SET soluong = soluong - :buy_qty WHERE name = :name");
             $db->bind(':buy_qty', $buy_qty);
             $db->bind(':name', $product_name_buy);
             $db->execute();
         }

         // xoá giỏ hàng sau khi đặt hàng
         $db->query("DELETE FROM `cart` WHERE user_id = :user_id");
         $db->bind(':user_id', $user_id);
         $db->execute();
         
         $message[] = 'Đặt hàng thành công!';
      }
   } else {
      $message[] = 'Giỏ hàng của bạn đang trống!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Bookept | Checkout</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
      $grand_total = 0;// Tổng giá trị giỏ hàng
      $db->query("SELECT * FROM `cart` WHERE user_id = :user_id");// Lấy các sản phẩm trong giỏ hàng của người dùng
      $db->bind(':user_id', $user_id);// Bind id người dùng
      $select_cart = $db->resultSet();// Lấy kết quả giỏ hàng
      ?>

      <div class="summary-order">
         <div class="summary-header">
            <h2><i class="fa-solid fa-cart-flatbed"></i> Giỏ hàng của bạn</h2>
            <h5 style="background: #888; border-radius: 50%; width:3.5rem; height:3.5rem; color:white; display:flex; justify-content:center; align-items:center"><?php echo count($select_cart) ?></h5>
         </div>
         <div class="summary-list">
            <?php
            if (count($select_cart) > 0) {
               foreach ($select_cart as $fetch_cart) {
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
   <script src="js/script.js"></script>
</body>
</html>