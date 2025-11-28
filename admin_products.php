<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) { header('location:login.php'); };

$db = new Database();

if (isset($_POST['add_product'])) {// Xử lý thêm sản phẩm mới
   $name = $_POST['name'];// Lấy tên sản phẩm
   $price = $_POST['price'];// Lấy giá sản phẩm
   $image = $_FILES['image']['name'];// Lấy tên hình ảnh
   $image_size = $_FILES['image']['size'];// Lấy kích thước hình ảnh
   $image_tmp_name = $_FILES['image']['tmp_name'];// Lấy tên tạm thời của hình ảnh
   $image_folder = 'uploaded_img/' . $image;// Đường dẫn lưu hình ảnh mới

   $db->query("SELECT name FROM `products` WHERE name = :name");// Kiểm tra sản phẩm đã tồn tại chưa
   $db->bind(':name', $name);
   $db->execute();

   if ($db->rowCount() > 0) {// Nếu sản phẩm đã tồn tại
      $message[] = 'tên sản phẩm đã được thêm vào trước đó!';
   } else {
      // Thêm sản phẩm mới vào CSDL
      $db->query("INSERT INTO `products`(name, price, image) VALUES(:name, :price, :image)");
      $db->bind(':name', $name);
      $db->bind(':price', $price);
      $db->bind(':image', $image);
      
      if ($db->execute()) {// Thực thi câu truy vấn
         if ($image_size > 2000000) {// Kiểm tra kích thước hình ảnh
            $message[] = 'kích thước hình ảnh quá lớn';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);// Di chuyển hình ảnh vào thư mục đã chỉ định
            $message[] = 'sản phẩm đã được thêm thành công!';
         }
      } else {
         $message[] = 'không thể thêm sản phẩm!';
      }
   }
}


// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];// Lấy id sản phẩm cần xóa
   
   $db->query("SELECT image FROM `products` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $fetch_delete_image = $db->single();
   
   // Xóa hình ảnh sản phẩm khỏi thư mục
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   
   // Xóa sản phẩm khỏi CSDL
   $db->query("DELETE FROM `products` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $db->execute();
   
   // Quay lại trang hiện tại với tham số page nếu có
   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   header('location:admin_products.php?page=' . $page);
   exit;
}

// Xử lý cập nhật sản phẩm
if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id']; // Lấy id sản phẩm cần cập nhật
   $update_name = $_POST['update_name']; // Lấy tên sản phẩm cần cập nhật
   $update_price = $_POST['update_price']; // Lấy giá sản phẩm cần cập nhật

   $db->query("UPDATE `products` SET name = :name, price = :price WHERE id = :id");// Cập nhật tên và giá sản phẩm
   $db->bind(':name', $update_name);// Bind tên sản phẩm
   $db->bind(':price', $update_price);// Bind giá sản phẩm
   $db->bind(':id', $update_p_id);
   $db->execute();

   $update_image = $_FILES['update_image']['name'];// Lấy tên hình ảnh mới
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];// Lấy tên tạm thời của hình ảnh mới
   $update_image_size = $_FILES['update_image']['size'];// Lấy kích thước hình ảnh mới
   $update_folder = 'uploaded_img/' . $update_image;// Đường dẫn lưu hình ảnh mới
   $update_old_image = $_POST['update_old_image'];// Lấy tên hình ảnh cũ

   // Cập nhật hình ảnh nếu có hình ảnh mới được tải lên
   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'image file size is too large';
      } else {
         $db->query("UPDATE `products` SET image = :image WHERE id = :id");
         $db->bind(':image', $update_image);
         $db->bind(':id', $update_p_id);
         $db->execute();
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/' . $update_old_image);
      }
   }

   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   header('location:admin_products.php?page=' . $page);
   exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin | Products</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/product.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>
<body>
   <?php include 'admin_header.php'; ?>
   <section class="products">
      <h1 class="title">sản phẩm cửa hàng</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <h3>thêm sản phẩm</h3>
         <input type="text" name="name" class="box" placeholder="nhập tên sản phẩm" required>
         <input type="number" min="0" name="price" class="box" placeholder="nhập giá sản phẩm" required>
         <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
         <input type="submit" value="thêm sản phẩm" name="add_product" class="btn">
      </form>
   </section>

   <section class="products">
      <h1 class="title">sản phẩm mới nhất</h1>
      <div class="box-container">
         <?php
         // Phân trang sản phẩm
         $products_per_page = 8;
         $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
         if ($current_page < 1) $current_page = 1;
         $offset = ($current_page - 1) * $products_per_page;

         $db->query("SELECT COUNT(*) AS total FROM `products`");
         $total_products_row = $db->single();
         $total_products = $total_products_row['total'];
         $total_pages = ceil($total_products / $products_per_page);

         $db->query("SELECT * FROM `products` LIMIT $offset, $products_per_page");
         $select_products = $db->resultSet();

         if (count($select_products) > 0) {
            foreach ($select_products as $fetch_products) {
         ?>
               <form action="" method="post" class="box">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>" class="price">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <div class="image">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  </div>
                  <div class="details">
                     <div class="name">
                        <img src="./public/card/name.svg" alt="name_icon">
                        <span title="<?php echo htmlspecialchars($fetch_products['name']); ?>"><?php echo htmlspecialchars($fetch_products['name']); ?></span>
                     </div>
                     <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                     <div class="qty-pri">
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <div class="price">
                           <span style="font-size:0.7em">$</span><?php echo $fetch_products['price']; ?>
                        </div>
                     </div>
                     <div class="action">
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>&page=<?php echo $current_page; ?>" class="option-btn">cập nhật</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>&page=<?php echo $current_page; ?>" class="delete-btn" onclick="return confirm('delete this product?');">xóa</a>
                     </div>
                  </div>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">chưa có sản phẩm nào được thêm vào!</p>';
         }
         ?>
      </div>
      <?php 
         $base_url = 'admin_products.php'; 
         include './components/pagination.php';
      ?>
   </section>

   <section class="edit-product-form">
      <?php
      // Hiển thị biểu mẫu cập nhật sản phẩm nếu có tham số update trong URL
      if (isset($_GET['update'])) { // Nếu có tham số update
         $update_id = $_GET['update'];// Lấy id sản phẩm cần cập nhật
         $db->query("SELECT * FROM `products` WHERE id = :id");// Lấy thông tin sản phẩm từ CSDL
         $db->bind(':id', $update_id);// Bind id sản phẩm
         $update_query = $db->resultSet();// Lấy kết quả truy vấn
         
         if (count($update_query) > 0) {
            foreach ($update_query as $fetch_update) {
      ?>
               <form action="admin_products.php?page=<?php echo $current_page; ?>" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                  <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
                  <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                  <input type="submit" value="update" name="update_product" class="btn">
                  <input type="reset" value="cancel" id="close-update" class="option-btn">
               </form>
      <?php
            }
         }
      } else {
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
      ?>
   </section>
   <script src="js/admin_script.js"></script>
   <script>
      if(document.querySelector('#close-update')){
         document.querySelector('#close-update').onclick = () => {
            document.querySelector('.edit-product-form').style.display = 'none';
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page') || 1;
            window.location.href = 'admin_products.php?page=' + page;
         }
      }
   </script>
</body>
</html>