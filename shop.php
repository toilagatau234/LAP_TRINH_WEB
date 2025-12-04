<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}

$db = new Database();

// xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
   // KIỂM TRA: Chỉ xử lý khi tồn tại đầy đủ các dữ liệu cần thiết
   if(isset($_POST['product_name']) && isset($_POST['product_price']) && isset($_POST['product_image']) && isset($_POST['product_quantity'])){
      
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_quantity = $_POST['product_quantity'];

      // kiểm tra tồn kho trong bảng products
      $db->query("SELECT quantity FROM `products` WHERE name = :name");
      $db->bind(':name', $product_name);
      $check_stock = $db->single();
      
      // kiểm tra nếu sản phẩm tồn tại và có thông tin tồn kho
      if ($check_stock) {
          if ($check_stock['quantity'] < $product_quantity) {
              $message[] = 'Sản phẩm không đủ số lượng tồn kho! (Còn: ' . $check_stock['quantity'] . ')';
          } else {
              // thêm sản phẩm vào giỏ
              $db->query("SELECT * FROM `cart` WHERE name = :name AND user_id = :user_id");
              $db->bind(':name', $product_name);
              $db->bind(':user_id', $user_id);
              $db->execute();

              if ($db->rowCount() > 0) {
                 $message[] = 'Sản phẩm đã có trong giỏ hàng!';
              } else {
                 $db->query("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(:user_id, :name, :price, :quantity, :image)");
                 $db->bind(':user_id', $user_id);
                 $db->bind(':name', $product_name);
                 $db->bind(':price', $product_price);
                 $db->bind(':quantity', $product_quantity);
                 $db->bind(':image', $product_image);
                 $db->execute();
                 $message[] = 'Đã thêm sản phẩm vào giỏ hàng!';
              }
          }
      } else {
          $message[] = 'Sản phẩm không tồn tại trong hệ thống!';
      }
   } else {
       $message[] = 'Lỗi dữ liệu sản phẩm!'; 
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

   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
         // --- logic phân trang ---
         $limit = 8;
         // sửa lỗi undefined variable $current_page
         $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
         if ($current_page < 1) $current_page = 1;
         $offset = ($current_page - 1) * $limit;

         // đếm tổng số sản phẩm
         $db->query("SELECT COUNT(*) as total FROM `products`");
         $row_count = $db->single();
         $total_pages = ceil($row_count['total'] / $limit);

         // lấy danh sách sản phẩm theo trang
         $db->query("SELECT * FROM `products` LIMIT $offset, $limit");
         $select_products = $db->resultSet();

         if (count($select_products) > 0) { 
            foreach ($select_products as $fetch_products) {
                // Xử lý số lượng kho an toàn
                $kho = isset($fetch_products['quantity']) ? $fetch_products['quantity'] : 0;
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
                        <?php echo htmlspecialchars($fetch_products['name']); ?>
                     </div>
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     
                     <div class="qty-pri">
                        <div class="price">
                           <span>$</span><?php echo $fetch_products['price']; ?>
                        </div>
                        
                        <div style="font-size: 1.4rem; color: #666; margin-top: 5px;">
                             Kho: 
                             <?php 
                                if($kho > 0) {
                                    echo "<span style='color:green'>$kho</span>";
                                } else {
                                    echo "<span style='color:red'>Hết hàng</span>";
                                }
                             ?>
                        </div>

                        <?php if($kho > 0): ?>
                            <input type="number" min="1" max="<?php echo $kho; ?>" name="product_quantity" value="1" class="qty">
                        <?php endif; ?>
                     </div>

                     <div class="action">
                        <?php if($kho > 0): ?>
                            <button type="submit" name="add_to_cart" class="btn"><img src="./public/card/cart.svg" alt="cart_icon"> Thêm</button>
                        <?php else: ?>
                            <button type="button" class="btn" style="background:#ccc; cursor: not-allowed;" disabled>Hết hàng</button>
                        <?php endif; ?>

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
         $base_url = 'shop.php';
         include 'components/pagination.php'; 
      ?>

   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>

</body>
</html>