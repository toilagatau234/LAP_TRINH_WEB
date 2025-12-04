<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
};

$db = new Database();

// --- XỬ LÝ THÊM SẢN PHẨM ---
if (isset($_POST['add_product'])) {
   $name = $_POST['name'];
   $price = $_POST['price'];
   $quantity = $_POST['quantity']; // Lấy số lượng
   $details = $_POST['details'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $db->query("SELECT name FROM `products` WHERE name = :name");
   $db->bind(':name', $name);
   $db->execute();

   if ($db->rowCount() > 0) {
      $message[] = 'Tên sản phẩm đã tồn tại!';
   } else {
      // Thêm sản phẩm kèm số lượng
      $db->query("INSERT INTO `products`(name, details, price, quantity, image) VALUES(:name, :details, :price, :quantity, :image)");
      $db->bind(':name', $name);
      $db->bind(':price', $price);
      $db->bind(':quantity', $quantity);
      $db->bind(':details', $details);
      $db->bind(':image', $image);

      if ($db->execute()) {
         if ($image_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Thêm sản phẩm thành công!';
         }
      } else {
         $message[] = 'Không thể thêm sản phẩm!';
      }
   }
}

// --- XỬ LÝ XÓA SẢN PHẨM ---
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   $db->query("SELECT image FROM `products` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $fetch_delete_image = $db->single();

   if (!empty($fetch_delete_image['image'])) {
      unlink('uploaded_img/' . $fetch_delete_image['image']);
   }

   $db->query("DELETE FROM `products` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $db->execute();

   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   header('location:admin_products.php?page=' . $page);
   exit;
}

// --- XỬ LÝ CẬP NHẬT SẢN PHẨM ---
if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_quantity = $_POST['update_quantity']; // Cập nhật số lượng
   $update_details = $_POST['update_details']; // Cập nhật chi tiết sản phẩm

   $db->query("UPDATE `products` SET name = :name, details = :details, price = :price, quantity = :quantity WHERE id = :id");
   $db->bind(':name', $update_name);
   $db->bind(':price', $update_price);
   $db->bind(':quantity', $update_quantity);
   $db->bind(':details', $update_details);
   $db->bind(':id', $update_p_id);
   $db->execute();

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'File ảnh quá lớn!';
      } else {
         $db->query("UPDATE `products` SET image = :image WHERE id = :id");
         $db->bind(':image', $update_image);
         $db->bind(':id', $update_p_id);
         $db->execute();
         move_uploaded_file($update_image_tmp_name, $update_folder);
         if (file_exists('uploaded_img/' . $update_old_image)) {
            unlink('uploaded_img/' . $update_old_image);
         }
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
   <title>Admin | Quản lý sản phẩm</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/product.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>

<body>
   <?php include 'admin_header.php'; ?>

   <section class="products">
      <h1 class="title">QUẢN LÝ SẢN PHẨM</h1>
      <form action="" method="post" enctype="multipart/form-data" class="add-product-form">
         <h3>Thêm sản phẩm mới</h3>
         <div class="input-group">
            <input type="text" name="name" class="box" placeholder="Nhập tên sản phẩm" required>
         </div>
         <div class="input-group">
            <input type="number" min="0" name="price" class="box" placeholder="Giá bán ($)" required>
         </div>
         <div class="input-group">
            <input type="number" min="0" name="quantity" class="box" placeholder="Số lượng tồn kho" required>
         </div>
         <div class="input-group">
            <textarea name="details" class="box" placeholder="Nhập mô tả sản phẩm" cols="30" rows="5" required></textarea>
         </div>
         <div class="input-group">
            <input type="file" name="image" ...>
         </div>
         <!-- <div class="input-group">
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
         </div> -->
         <input type="submit" value="Thêm ngay" name="add_product" class="btn">
      </form>
   </section>

   <section class="products">
      <h1 class="title">Danh sách hiện có</h1>
      <div class="box-container">
         <?php
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
               <div class="box">
                  <div class="image">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  </div>
                  <div class="details">
                     <div class="name">
                        <img src="./public/card/name.svg" alt="name_icon">
                        <span><?php echo htmlspecialchars($fetch_products['name']); ?></span>
                     </div>

                     <div class="qty-pri" style="flex-direction: column; align-items: stretch;">
                        <div class="price" style="margin-bottom: 5px; font-size: 2rem; color: var(--red);">
                           $<?php echo $fetch_products['price']; ?>
                        </div>

                        <div class="qty-display">
                           Kho:
                           <span class="<?php echo ($fetch_products['quantity'] <= 0) ? 'out-of-stock' : ''; ?>">
                              <?php echo ($fetch_products['quantity'] > 0) ? $fetch_products['quantity'] : 'Hết hàng'; ?>
                           </span>
                        </div>
                     </div>

                     <div class="action">
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>&page=<?php echo $current_page; ?>" class="option-btn">Sửa</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>&page=<?php echo $current_page; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a>
                     </div>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Chưa có sản phẩm nào được thêm!</p>';
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
      if (isset($_GET['update'])) {
         $update_id = $_GET['update'];
         $db->query("SELECT * FROM `products` WHERE id = :id");
         $db->bind(':id', $update_id);
         $update_query = $db->resultSet();

         if (count($update_query) > 0) {
            foreach ($update_query as $fetch_update) {
      ?>
               <form action="admin_products.php?page=<?php echo $current_page; ?>" method="post" enctype="multipart/form-data">
                  <h3 style="margin-bottom: 2rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Cập nhật sản phẩm</h3>

                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

                  <div class="row-grid">

                     <div class="col-left">
                        <div class="image-preview">
                           <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                        </div>

                        <div class="input-group">
                           <label>Tên sản phẩm:</label>
                           <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên sản phẩm">
                        </div>

                        <div class="flex-input">
                           <div class="input-group">
                              <label>Giá ($):</label>
                              <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Giá">
                           </div>
                           <div class="input-group">
                              <label>Kho:</label>
                              <input type="number" name="update_quantity" value="<?php echo $fetch_update['quantity']; ?>" min="0" class="box" required placeholder="Số lượng">
                           </div>
                        </div>

                        <div class="input-group">
                           <label>Chọn ảnh mới:</label>
                           <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                        </div>
                     </div>

                     <div class="col-right">
                        <label>Mô tả chi tiết:</label>
                        <textarea name="update_details" class="box description-box" required placeholder="Nhập mô tả sản phẩm..."><?php echo $fetch_update['details']; ?></textarea>
                     </div>
                  </div>

                  <div class="btn-container">
                     <input type="submit" value="Lưu thay đổi" name="update_product" class="btn">
                     <input type="reset" value="Hủy bỏ" id="close-update" class="option-btn">
                  </div>
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
      // Script đóng modal update
      if (document.querySelector('#close-update')) {
         document.querySelector('#close-update').onclick = () => {
            document.querySelector('.edit-product-form').style.display = 'none';
            // Reset URL để bỏ tham số ?update=...
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page') || 1;
            window.location.href = 'admin_products.php?page=' + page;
         }
      }
   </script>
</body>

</html>