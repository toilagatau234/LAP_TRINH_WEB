<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){ header('location:login.php'); };

$db = new Database();

if(isset($_POST['add_to_cart'])){
   // Logic thêm vào giỏ hàng (giống shop.php)
   $product_name = $_POST['product_name']; // Lấy tên sản phẩm
   $product_price = $_POST['product_price']; // Lấy giá sản phẩm
   $product_image = $_POST['product_image']; // Lấy hình ảnh sản phẩm
   $product_quantity = $_POST['product_quantity']; // Lấy số lượng sản phẩm

   // Chuẩn bị câu truy vấn kiểm tra sản phẩm đã có trong giỏ hàng chưa
   $db->query("SELECT * FROM `cart` WHERE name = :name AND user_id = :user_id");
   $db->bind(':name', $product_name);// Bind tên sản phẩm
   $db->bind(':user_id', $user_id);// Bind id người dùng
   $db->execute();

   if($db->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      // Thêm sản phẩm vào giỏ hàng
      $db->query("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(:user_id, :name, :price, :quantity, :image)");
      $db->bind(':user_id', $user_id);// Bind id người dùng
      $db->bind(':name', $product_name);// Bind tên sản phẩm
      $db->bind(':price', $product_price);// Bind giá sản phẩm
      $db->bind(':quantity', $product_quantity);// Bind số lượng sản phẩm
      $db->bind(':image', $product_image);// Bind hình ảnh sản phẩm
      $db->execute();
      $message[] = 'product added to cart!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Bookept | Search</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<?php include 'header.php'; ?>
<div class="heading">
   <h3>Tìm kiếm sách</h3>
   <p> <a href="home.php">Trang chủ</a> / Tìm kiếm </p>
</div>
<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="tìm kiếm sản phẩm..." class="box">
      <input type="submit" name="submit" value="tìm kiếm" class="btn">
   </form>
</section>
<section class="products" style="padding-top: 0;">
   <div class="box-container">
   <?php
      if(isset($_POST['submit'])){ // Nếu người dùng đã gửi biểu mẫu tìm kiếm
         $search_item = $_POST['search'];// Lấy từ khóa tìm kiếm
         // Sử dụng ký tự đại diện % cho LIKE
         $db->query("SELECT * FROM `products` WHERE name LIKE :search");// Chuẩn bị câu truy vấn tìm kiếm
         $db->bind(':search', "%{$search_item}%");// Bind từ khóa tìm kiếm với ký tự đại diện
         $select_products = $db->resultSet();// Lấy kết quả tìm kiếm
         
         if(count($select_products) > 0){// Nếu có sản phẩm tìm thấy
            foreach($select_products as $fetch_product){// Lặp qua từng sản phẩm
   ?>
   <form action="" method="post" class="box">
      <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
      <div class="name"><?php echo $fetch_product['name']; ?></div>
      <div class="price">$<?php echo $fetch_product['price']; ?></div>
      <input type="number"  class="qty" name="product_quantity" min="1" value="1">
      <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
      <input type="submit" class="btn" value="add to cart" name="add_to_cart">
   </form>
   <?php
            }
         }else{
            echo '<p class="empty">không tìm thấy kết quả!</p>';
         }
      }else{
         echo '<p class="empty">bạn hãy tìm kiếm gì đó!</p>';
      }
   ?>
   </div>
</section>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>