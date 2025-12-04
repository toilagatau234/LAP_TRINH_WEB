<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
   header('location:login.php');
}

$db = new Database();

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) { // Logic thêm vào giỏ hàng
   $product_name = $_POST['product_name']; // Lấy tên sản phẩm
   $product_price = $_POST['product_price']; // Lấy giá sản phẩm
   $product_image = $_POST['product_image']; // Lấy hình ảnh sản phẩm
   $product_quantity = $_POST['product_quantity']; // Lấy số lượng sản phẩm

   $db->query("SELECT * FROM `cart` WHERE name = :name AND user_id = :user_id"); // Chuẩn bị câu truy vấn kiểm tra sản phẩm đã có trong giỏ hàng chưa
   $db->bind(':name', $product_name); // Bind tên sản phẩm
   $db->bind(':user_id', $user_id); // Bind id người dùng
   $db->execute();

   if ($db->rowCount() > 0) { // Nếu sản phẩm đã có trong giỏ hàng
      $message[] = 'Sản phẩm đã có trong giỏ hàng!';
   } else {
      $db->query("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(:user_id, :name, :price, :quantity, :image)"); // Chuẩn bị câu truy vấn thêm sản phẩm vào giỏ hàng
      $db->bind(':user_id', $user_id); // Bind id người dùng
      $db->bind(':name', $product_name); // Bind tên sản phẩm
      $db->bind(':price', $product_price); // Bind giá sản phẩm
      $db->bind(':quantity', $product_quantity); // Bind số lượng sản phẩm
      $db->bind(':image', $product_image); // Bind hình ảnh sản phẩm
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
   <title>Bookept | Home</title>

   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/service.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <section class="home">
      <div class="content">
         <h3>Sách được chọn lọc kỹ lưỡng giao tận nhà bạn.</h3>
         <p>Chào mừng đến với Bookept - Nơi tri thức hội tụ.</p>
         <a href="about.php" class="white-btn">Khám phá thêm</a>
      </div>
   </section>

   <section class="products">
      <h1 class="title">Sản phẩm mới nhất</h1>
      <div class="box-container">
         <?php
         $db->query("SELECT * FROM `products` LIMIT 8");
         $select_products = $db->resultSet();

         if (count($select_products) > 0) {
            foreach ($select_products as $fetch_products) {
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
                           if ($kho > 0) {
                              echo "<span style='color:green'>$kho</span>";
                           } else {
                              echo "<span style='color:red'>Hết hàng</span>";
                           }
                           ?>
                        </div>

                        <?php if ($kho > 0): ?>
                           <input type="number" min="1" max="<?php echo $kho; ?>" name="product_quantity" value="1" class="qty">
                        <?php endif; ?>
                     </div>

                     <div class="action">
                        <?php if ($kho > 0): ?>
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
            echo '<p class="empty">Chưa có sản phẩm nào được thêm vào!</p>';
         }
         ?>
      </div>

      <div class="load-more" style="margin-top: 3rem; text-align:center">
         <a href="shop.php" class="transparent-btn">Xem thêm sản phẩm...</a>
      </div>
   </section>

   <section class="home-contact">
      <div>
         <img src="https://cdn.pixabay.com/photo/2022/03/01/08/11/call-center-7040784_960_720.png" alt="" style="border-radius: 1rem; width:32rem; height:25rem">
      </div>
      <div class="content">
         <div class="service-title">
            <h3>Bạn có câu hỏi nào không?</h3>
         </div>
         <div class="service-content">
            <p>Đội ngũ chăm sóc 24/7 sẵn sàng trả lời mọi câu hỏi của bạn.</p>
            <p>Liên hệ với chúng tôi để được hỗ trợ dịch vụ tốt nhất!</p>
         </div>
         <div class="service-feature">
            <p><img src="./public/service/tick.svg" alt="tick">24/7</p>
            <p><img src="./public/service/tick.svg" alt="tick">Nhanh chóng</p>
            <p><img src="./public/service/tick.svg" alt="tick">Thân thiện</p>
            <p><img src="./public/service/tick.svg" alt="tick">Nhiệt tình</p>
            <p><img src="./public/service/tick.svg" alt="tick">Chuyên nghiệp</p>
         </div>
         <div>
            <a href="contact.php" class="option-btn">Liên hệ ngay</a>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <div id="fcircle" onclick="scrollToTop()">
      <img src="public/icon/scroll-up-circle.svg" alt="Move up">
   </div>

   <script src="js/script.js"></script>

</body>

</html>