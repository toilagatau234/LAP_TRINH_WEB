<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}

$db = new Database();

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {// Logic thêm vào giỏ hàng
   $product_name = $_POST['product_name']; // Lấy tên sản phẩm
   $product_price = $_POST['product_price'];// Lấy giá sản phẩm
   $product_image = $_POST['product_image'];// Lấy hình ảnh sản phẩm
   $product_quantity = $_POST['product_quantity'];// Lấy số lượng sản phẩm

   $db->query("SELECT * FROM `cart` WHERE name = :name AND user_id = :user_id");// Chuẩn bị câu truy vấn kiểm tra sản phẩm đã có trong giỏ hàng chưa
   $db->bind(':name', $product_name);// Bind tên sản phẩm
   $db->bind(':user_id', $user_id);// Bind id người dùng
   $db->execute();

   if ($db->rowCount() > 0) {
      $message[] = 'Sản phẩm đã có trong giỏ hàng!';
   } else {
      $db->query("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(:user_id, :name, :price, :quantity, :image)");
      $db->bind(':user_id', $user_id);// Bind id người dùng
      $db->bind(':name', $product_name);// Bind tên sản phẩm
      $db->bind(':price', $product_price);// Bind giá sản phẩm
      $db->bind(':quantity', $product_quantity);// Bind số lượng sản phẩm
      $db->bind(':image', $product_image);// Bind hình ảnh sản phẩm
      $db->execute();
      $message[] = 'Đã thêm sản phẩm vào giỏ hàng!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Shop</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/service.css">
   <link rel="stylesheet" href="styles/pagination.css"> 
</head>
<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Cửa hàng của chúng tôi</h3>
      <p> <a href="home.php">Trang chủ</a> / Cửa hàng </p>
   </div>

   <section class="products">
      <h1 class="title">Sản phẩm mới nhất</h1>
      <div class="box-container">
         <?php
         // --- LOGIC PHÂN TRANG ---
         $limit = 8;
         $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
         if ($current_page < 1) $current_page = 1;
         $offset = ($current_page - 1) * $limit;

         // Đếm tổng số sản phẩm
         $db->query("SELECT COUNT(*) as total FROM `products`"); // Chuẩn bị câu truy vấn đếm tổng số sản phẩm
         $row_count = $db->single();// Lấy kết quả đếm
         $total_pages = ceil($row_count['total'] / $limit);// Tính tổng số trang

         // Lấy danh sách sản phẩm theo trang
         $db->query("SELECT * FROM `products` LIMIT $offset, $limit");// Chuẩn bị câu truy vấn lấy sản phẩm với phân trang
         $select_products = $db->resultSet();// Lấy kết quả sản phẩm

         if (count($select_products) > 0) { // Kiểm tra có sản phẩm không
            foreach ($select_products as $fetch_products) {// Lặp qua từng sản phẩm
         ?>
               <form action="" method="post" class="box">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                  <div class="image">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  </div>
                  <div class="details">
                     <div class="name">
                        <img src="./public/card/name.svg" alt="name_icon">
                        <?php echo $fetch_products['name']; ?>
                     </div>
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     <div class="qty-pri">
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <div class="price">
                           <span style="font-size:0.7em">$</span><?php echo $fetch_products['price']; ?>
                        </div>
                     </div>
                     <div class="action">
                        <button type="submit" name="add_to_cart"><img src="./public/card/cart.svg" alt="cart_icon">Thêm vào giỏ</button>
                        <a href="detail_product.php?id=<?php echo $fetch_products['id']; ?>" class="option-btn" style="padding: 0.8rem 1.5rem;"><i class="fas fa-eye"></i></a>
                     </div>
                  </div>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">Chưa có sản phẩm nào!</p>';
         }
         ?>
      </div>

      <?php 
         // --- GỌI COMPONENT PHÂN TRANG ---
         $base_url = 'shop.php';
         include 'components/pagination.php'; 
      ?>

   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>

</body>
</html>