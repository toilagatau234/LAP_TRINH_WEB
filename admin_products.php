<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};

if (isset($_POST['add_product'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` 
      WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'tên sản phẩm đã được thêm vào trước đó!';
   } else {
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) 
         VALUES('$name', '$price', '$image')") or die('query failed');

      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'kích thước hình ảnh quá lớn';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'sản phẩm đã được thêm thành công!';
         }
      } else {
         $message[] = 'không thể thêm sản phẩm!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   
   // Giữ lại trang khi xóa
   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   header('location:admin_products.php?page=' . $page);
   exit; // Thêm exit để dừng thực thi
}

if (isset($_POST['update_product'])) {

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'image file size is too large';
      } else {
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/' . $update_old_image);
      }
   }

   // Giữ lại trang khi cập nhật
   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   header('location:admin_products.php?page=' . $page);
   exit; // Thêm exit để dừng thực thi
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
         // xử lý Phân trang (LOGIC VẪN GIỮ NGUYÊN)
         $products_per_page = 8; //đặt số sản phẩm hiểm thị trong 1 page là 8
         $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //lấy số trang hiện tại từ URL, nếu không có thì mặc định là trang 1
         if ($current_page < 1) { //trang không thể nhỏ hơn 1
            $current_page = 1;
         }
         $offset = ($current_page - 1) * $products_per_page; //tính toán vị trí bắt đầu lấy sản phẩm từ CSDL

         // Đếm tổng số sản phẩm
         $total_products_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `products`") or die('query failed'); //truy vấn đếm tổng số sản phẩm
         $total_products_row = mysqli_fetch_assoc($total_products_query);
         $total_products = $total_products_row['total'];//lấy tổng số sản phẩm từ kết quả truy vấn
         $total_pages = ceil($total_products / $products_per_page);// tính tổng số trang cần thiết

         // Lấy sản phẩm với phân trang
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT $offset, $products_per_page") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
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
         // Định nghĩa biến $base_url cho component
         $base_url = 'admin_products.php'; 
         // Gọi component
         include './components/pagination.php';
      ?>

   </section>

   <section class="edit-product-form">

      <?php
      if (isset($_GET['update'])) {
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
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
      //nút close-update giữ trang hiện tại khi đóng form chỉnh sửa
      // Đoạn script này vẫn giữ nguyên như bạn đã cung cấp
      if(document.querySelector('#close-update')){
         document.querySelector('#close-update').onclick = () => {
            document.querySelector('.edit-product-form').style.display = 'none';// Ẩn form chỉnh sửa

            const urlParams = new URLSearchParams(window.location.search);// Lấy tham số URL
            const page = urlParams.get('page') || 1;// Lấy trang hiện tại hoặc về trang 1 nếu không có
            window.location.href = 'admin_products.php?page=' + page;// Chuyển hướng về trang hiện tại
         }
      }
   </script>

</body>

</html>