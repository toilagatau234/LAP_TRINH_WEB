<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){ header('location:login.php'); }

$db = new Database();

if(isset($_POST['update_cart'])){// Logic cập nhật số lượng giỏ hàng
   $cart_id = $_POST['cart_id'];// Lấy id giỏ hàng
   $cart_quantity = $_POST['cart_quantity'];// Lấy số lượng giỏ hàng mới
   $db->query("UPDATE `cart` SET quantity = :quantity WHERE id = :id");// Chuẩn bị câu truy vấn cập nhật số lượng
   $db->bind(':quantity', $cart_quantity);
   $db->bind(':id', $cart_id);
   $db->execute();
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){// Logic xóa sản phẩm khỏi giỏ hàng
   $delete_id = $_GET['delete'];// Lấy id sản phẩm cần xóa
   $db->query("DELETE FROM `cart` WHERE id = :id");// Chuẩn bị câu truy vấn xóa sản phẩm
   $db->bind(':id', $delete_id);// Bind id sản phẩm
   $db->execute();// Thực thi câu truy vấn
   header('location:cart.php');// Chuyển hướng về trang giỏ hàng
}

if(isset($_GET['delete_all'])){// Logic xóa tất cả sản phẩm trong giỏ hàng
   $db->query("DELETE FROM `cart` WHERE user_id = :user_id");// Chuẩn bị câu truy vấn xóa tất cả sản phẩm
   $db->bind(':user_id', $user_id);// Bind id người dùng
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
   <h3>giỏ hàng</h3>
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
            <option value="default">mặc định</option>
            <option value="alphabet">a-z</option>
            <option value="low_to_high_price">Giá thấp đến cao</option>
         </select>
      </div>
   </div>
   <ul class="cart-list">
      <?php
         $grand_total = 0;
         if(count($cart_items) > 0){
            foreach($cart_items as $fetch_cart){   
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
               <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            </div>
            <div class="item-price">
               <div>
                  <div class="price">$<?php echo $fetch_cart['price']; ?> <span style="font-size: 1em; color:#888"> &bull; (<?php echo $sub_total = ($fetch_cart['quantity']); ?>)</span></div>
                  <div class="sub-total"> sub total : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></span></div>
               </div>
            </div>
            <div class="item-btn">
               <button type="submit" name="update_cart" value="update" class="option-btn">Cập nhập</button>
            </div>
            <div class="item-delete">
               <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
            </div>
         </form>
      </li>
      <?php
      $grand_total += ($fetch_cart['quantity'] * $fetch_cart['price']);
         }
      }else{
         echo '<p class="empty">giỏ hàng của bạn đang trống!</p>';
      }
      ?>
      <li class="cart-action">
         <div class="cart-btn">
            <a href="shop.php" class="option-btn"><img src="./public/cart/continue.svg" alt="continue_icon">tiếp tục mua sắm</a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>"><img src="./public/cart/checkout.svg" alt="checkout_icon">tiến hành thanh toán</a>
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');"><img src="./public/cart/remove.svg" alt="delete_all_icon">xóa tất cả</a>
         </div>
         <div class="cart-total">
            <p>tổng cộng : <span>$<?php echo $grand_total; ?></span></p>
         </div>
      </li>
   </ul>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>