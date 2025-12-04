<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}

$db = new Database();

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Chi tiết sản phẩm</title>

   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/detail_product.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Chi tiết sản phẩm</h3>
      <p> <a href="home.php">Trang chủ</a> / Chi tiết </p>
   </div>

   <section class="quick-view">
      <?php
      if (isset($_GET['id'])) {
         $pid = $_GET['id'];

         // Truy vấn lấy thông tin sản phẩm
         $db->query("SELECT * FROM `products` WHERE id = :id");
         $db->bind(':id', $pid);
         $product = $db->single();

         if ($product) {
      ?>

            <div class="product-detail-card">

               <div class="image-col">
                  <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
               </div>

               <div class="content-col">
                  <div class="name"><?php echo $product['name']; ?></div>
                  <div class="price">$<?php echo $product['price']; ?></div>

                  <div class="meta-info">
                     Tình trạng:
                     <?php
                     $kho = $product['quantity'];
                     echo ($kho > 0) ? "<span style='color:green'>Còn hàng ($kho)</span>" : "<span style='color:red'>Hết hàng</span>";
                     ?>
                  </div>

                  <div class="description">
                     <b>Mô tả sản phẩm:</b><br>
                     <?php echo nl2br(htmlspecialchars($product['details'])); ?>
                  </div>

                  <form action="" method="post" class="cart-form">
                     <?php if ($kho > 0): ?>
                        <input type="number" min="1" max="<?php echo $kho; ?>" name="product_quantity" value="1" class="qty">
                        <button type="submit" name="add_to_cart" class="btn">
                           <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                        </button>
                     <?php else: ?>
                        <button type="button" class="btn" style="background:#ccc;" disabled>Tạm hết hàng</button>
                     <?php endif; ?>
                  </form>
               </div>
            </div>
      <?php
         } else {
            echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
         }
      } else {
         echo '<p class="empty">Chưa chọn sản phẩm nào!</p>';
      }
      ?>
   </section>

   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>