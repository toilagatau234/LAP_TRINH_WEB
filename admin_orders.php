<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
}

<<<<<<< Updated upstream
<form action="" method="post">
   <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">

   <select name="update_payment">
      <option value="" disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
      <option value="pending">Đang xử lý</option>
      <option value="completed">Thành công</option>
   </select>

   <input type="submit" name="update_order" value="Cập nhật trạng thái" class="option-btn" style="margin-top:10px;">
</form>

<a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" 
   class="delete-btn" 
   onclick="return confirm('Bạn chắc muốn xóa đơn hàng này?');">
   Xóa đơn hàng
</a>
=======
$db = new Database();

// --- XỬ LÝ CẬP NHẬT TRẠNG THÁI ---
if (isset($_POST['update_order'])) {// Kiểm tra nếu nút cập nhật được nhấn
   if (isset($_POST['update_payment'])) {// Kiểm tra nếu trạng thái được chọn
      $order_update_id = $_POST['order_id'];// Lấy id đơn hàng cần cập nhật
      $update_payment = $_POST['update_payment'];// Lấy trạng thái mới
>>>>>>> Stashed changes

      $db->query("UPDATE `orders` SET payment_status = :status WHERE id = :id");// Chuẩn bị câu truy vấn cập nhật trạng thái
      $db->bind(':status', $update_payment);// Bind trạng thái mới
      $db->bind(':id', $order_update_id);// Bind id đơn hàng
      $db->execute();
      $message[] = 'Cập nhật trạng thái thành công!';
   } else {
      $message[] = 'Vui lòng chọn trạng thái trước!';
   }
}

// --- XỬ LÝ XÓA ---
if (isset($_GET['delete'])) {// Kiểm tra nếu có yêu cầu xóa
   $delete_id = $_GET['delete'];// Lấy id đơn hàng cần xóa
   $db->query("DELETE FROM `orders` WHERE id = :id");// Chuẩn bị câu truy vấn xóa
   $db->bind(':id', $delete_id);// Bind id đơn hàng
   $db->execute();
   header('location:admin_orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>Admin | Quản lý đơn hàng</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./styles/admin.css">
   <link rel="stylesheet" href="./styles/admin/orders-admin.css">
   <link rel="stylesheet" href="./styles/pagination.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="orders">

<<<<<<< Updated upstream
   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> ID người dùng : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> Thời gian đặt : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> Tên : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> Số điện thoại : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Địa chỉ : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> Tổng sản phẩm : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> Tổng giá : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> Phương thức thanh toán : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <form action="" method="post">
   <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">

   <select name="update_payment">
      <option value="" disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
      <option value="pending">Đang xử lý</option>
      <option value="completed">Thành công</option>
   </select>

   <input type="submit" name="update_order" 
          value="Cập nhật trạng thái" 
          class="option-btn" 
          style="margin-top:10px;">
</form>

<a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" 
   class="delete-btn"
   onclick="return confirm('Bạn chắc muốn xóa đơn hàng này?');">
   Xóa đơn hàng
</a>

=======
      <h1 class="title">QUẢN LÝ ĐƠN HÀNG</h1>

      <div class="admin-card">
         <div class="card-header">
            <h3>Danh sách đơn đặt hàng</h3>
         </div>
>>>>>>> Stashed changes

         <div class="card-body">
            <table class="custom-table">
               <thead>
                  <tr>
                     <th>ID / Ngày</th>
                     <th>Thông tin khách hàng</th>
                     <th style="width: 30%;">Sản phẩm</th>
                     <th>Tổng tiền</th>
                     <th>Địa chỉ</th>
                     <th>Trạng thái</th>
                     <th>Hành động</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  // --- LOGIC PHÂN TRANG ---
                  $limit = 8;
                  $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  if ($current_page < 1) $current_page = 1;
                  $offset = ($current_page - 1) * $limit;

                  // Đếm tổng
                  $db->query("SELECT COUNT(*) as total FROM `orders`");
                  $row_count = $db->single();
                  $total_pages = ceil($row_count['total'] / $limit);

                  // Lấy dữ liệu
                  $db->query("SELECT * FROM `orders` ORDER BY placed_on DESC LIMIT $offset, $limit");
                  $select_orders = $db->resultSet();

                  if (count($select_orders) > 0) {
                     foreach ($select_orders as $fetch_orders) {
                  ?>
                        <tr>
                           <td>
                              <b>#<?php echo $fetch_orders['id']; ?></b><br>
                              <small style="color:#666;"><?php echo $fetch_orders['placed_on']; ?></small>
                           </td>

                           <td class="info-group">
                              <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($fetch_orders['name']); ?></span>
                              <span><i class="fas fa-phone"></i> <?php echo $fetch_orders['number']; ?></span>
                              <span><i class="fas fa-envelope"></i> <?php echo $fetch_orders['email']; ?></span>
                           </td>

                           <td>
                              <?php echo $fetch_orders['total_products']; ?>
                           </td>

                           <td>
                              <span class="highlight-price">$<?php echo $fetch_orders['total_price']; ?></span><br>
                              <span class="payment-method"><?php echo $fetch_orders['method']; ?></span>
                           </td>

                           <td><?php echo $fetch_orders['address']; ?></td>

                           <td>
                              <form action="" method="post" class="status-form">
                                 <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                                 <select name="update_payment" class="status-select">
                                    <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                    <option value="pending">pending</option>
                                    <option value="completed">completed</option>
                                 </select>
                                 <button type="submit" name="update_order" class="btn-action btn-update" title="Lưu trạng thái">
                                    <i class="fas fa-save"></i>
                                 </button>
                              </form>
                           </td>

                           <td>
                              <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Xóa đơn hàng này?');" class="btn-action btn-delete" title="Xóa đơn hàng">
                                 <i class="fas fa-trash"></i>
                              </a>
                           </td>
                        </tr>
                  <?php
                     }
                  } else {
                     echo '<tr><td colspan="7" style="text-align:center; padding:2rem;">Chưa có đơn hàng nào!</td></tr>';
                  }
                  ?>
               </tbody>
            </table>
         </div>
      </div>

      <?php
      $base_url = 'admin_orders.php';
      include 'components/pagination.php';
      ?>

   </section>
   <script src="js/admin_script.js"></script>
</body>
<<<<<<< Updated upstream
</html>
=======

</html>
>>>>>>> Stashed changes
