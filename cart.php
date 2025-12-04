 <?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}

$db = new Database();

// cử lý cập nhật giỏ hàng
if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   // lấy tên sản phẩm từ giỏ hàng để tra cứu kho
   $db->query("SELECT name FROM `cart` WHERE id = :id");
   $db->bind(':id', $cart_id);
   $cart_item = $db->single();

   if ($cart_item) {
      $product_name = $cart_item['name'];

      // kiểm tra tồn kho trong bảng products
      $db->query("SELECT quantity FROM `products` WHERE name = :name");
      $db->bind(':name', $product_name);
      $product = $db->single();

      if ($product) {
         if ($product['quantity'] >= $cart_quantity) {
            // Nếu đủ hàng -> Cập nhật
            $db->query("UPDATE `cart` SET quantity = :quantity WHERE id = :id");
            $db->bind(':quantity', $cart_quantity);
            $db->bind(':id', $cart_id);
            $db->execute();
            $message[] = 'Đã cập nhật số lượng giỏ hàng!';
         } else {
            // Nếu thiếu hàng -> Báo lỗi
            $message[] = 'Kho không đủ hàng! (Chỉ còn: ' . $product['quantity'] . ')';
         }
      }
   }
   // Đã xóa đoạn code update thừa ở đây
}

// --- XỬ LÝ XÓA SẢN PHẨM ---
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $db->query("DELETE FROM `cart` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $db->execute();
   header('location:cart.php');
}

// --- XỬ LÝ XÓA TẤT CẢ ---
if (isset($_GET['delete_all'])) {
   $db->query("DELETE FROM `cart` WHERE user_id = :user_id");
   $db->bind(':user_id', $user_id);
   $db->execute();
   header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Bookept | Cart</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/cart.css">
</head>
<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Giỏ hàng</h3>
      <p> <a href="home.php">Trang chủ</a> / Giỏ hàng </p>
   </div>

   <section class="cart-container">
      <div class="cart-head">
         <?php
         $db->query("SELECT * FROM `cart` WHERE user_id = :user_id");
         $db->bind(':user_id', $user_id);
         $cart_items = $db->resultSet();
         ?>
         <div class="head-left">
            <h2>Danh sách của tôi</h2>
            <h6>&bull; <?php echo count($cart_items); ?> mặt hàng</h6>
         </div>
         <div>
            <select name="sort_cart" id="sort_cart">
               <option value="default">Mặc định</option>
               <option value="alphabet">A-Z</option>
               <option value="low_to_high_price">Giá thấp đến cao</option>
            </select>
         </div>
      </div>

      <ul class="cart-list">
         <?php
         $grand_total = 0;
         if (count($cart_items) > 0) {
            foreach ($cart_items as $fetch_cart) {
               // Lấy thông tin tồn kho (quantity) để giới hạn input max
               $db->query("SELECT quantity FROM `products` WHERE name = :name");
               $db->bind(':name', $fetch_cart['name']);
               $stock_info = $db->single();
               
               // Nếu tìm thấy sản phẩm thì lấy số lượng, ngược lại mặc định là 1 (tránh lỗi)
               $max_qty = ($stock_info) ? $stock_info['quantity'] : 1; 
         ?>
               <li class="cart-item">
                  <div class="cart-item-content">
                     <div class="image">
                        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                     </div>
                     <div class="name">
                        <h2><?php echo $fetch_cart['name']; ?></h2>
                        <p>#id: <?php echo $fetch_cart['id']; ?></p>
                     </div>
                  </div>

                  <form action="" method="post" class="cart-item-metrics">
                     <div class="item-quantity">
                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                        <input type="number" min="1" max="<?php echo $max_qty; ?>" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                        <br><small style="color:#666">(Kho: <?php echo $max_qty; ?>)</small>
                     </div>

                     <div class="item-price">
                        <div>
                           <div class="price">$<?php echo $fetch_cart['price']; ?> <span style="font-size: 1em; color:#888"> &times; <?php echo $fetch_cart['quantity']; ?></span></div>
                           <div class="sub-total"> Thành tiền : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></span></div>
                        </div>
                     </div>

                     <div class="item-btn">
                        <button type="submit" name="update_cart" value="update" class="option-btn">Cập nhật</button>
                     </div>

                     <div class="item-delete">
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?');"></a>
                     </div>
                  </form>
               </li>
         <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">Giỏ hàng của bạn đang trống!</p>';
         }
         ?>

         <li class="cart-action">
            <div class="cart-btn">
               <a href="shop.php" class="option-btn"><img src="./public/cart/continue.svg" alt="continue_icon">Tiếp tục mua sắm</a>
               
               <a href="checkout.php" class="btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">
                  <img src="./public/cart/checkout.svg" alt="checkout_icon">Tiến hành thanh toán
               </a>
               
               <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Xóa tất cả sản phẩm trong giỏ?');">
                  <img src="./public/cart/remove.svg" alt="delete_all_icon">Xóa tất cả
               </a>
            </div>
            <div class="cart-total">
               <p>Tổng cộng : <span>$<?php echo $grand_total; ?></span></p>
            </div>
         </li>
      </ul>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html>