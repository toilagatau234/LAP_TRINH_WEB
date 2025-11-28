<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
}
if(isset($_POST['update_user'])){
   $id = $_POST['update_id'];
   $name = $_POST['name'];
   $email = $_POST['email'];
   $user_type = $_POST['user_type'];
   $password = $_POST['password'];

   if(!empty($password)){
      $password = md5($password);
      mysqli_query($conn, "UPDATE `users` 
         SET name='$name', email='$email', user_type='$user_type', password='$password'
         WHERE id='$id'") or die('query failed');
   } else {
      mysqli_query($conn, "UPDATE `users` 
         SET name='$name', email='$email', user_type='$user_type'
         WHERE id='$id'") or die('query failed');
   }

   header('location:admin_users.php');
}

$db = new Database();

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $db->query("DELETE FROM `users` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $db->execute();
   header('location:admin_users.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>Admin | Quản lý người dùng</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./styles/admin.css">
   <link rel="stylesheet" href="./styles/admin/users-admin.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="users">
      <h1 class="title"> Danh sách người dùng </h1>

<<<<<<< Updated upstream
   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <p> ID người dùng : <span><?php echo $fetch_users['id']; ?></span> </p>
         <p> Tên người dùng : <span><?php echo $fetch_users['name']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_users['email']; ?></span> </p>
         <p> Loại người dùng : <span style="color:<?php if($fetch_users['user_type'] == 'admin')
            { echo 'var(--orange)'; } ?>"><?php echo $fetch_users['user_type']; ?></span> </p>
<a href="admin_users.php?edit=<?php echo $fetch_users['id']; ?>" class="option-btn">chỉnh sửa</a>
         <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>
            " onclick="return confirm('delete this user?');" class="delete-btn">xóa người dùng</a>
         <?php
if(isset($_GET['edit'])){
   $edit_id = $_GET['edit'];
   $select_edit = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$edit_id'") or die('query failed');
   $row = mysqli_fetch_assoc($select_edit);
?>
<div class="box update-box">
   <h3>Chỉnh sửa người dùng</h3>

   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">

      <p>Tên người dùng</p>
      <input type="text" name="name" value="<?php echo $row['name']; ?>" required>

      <p>Email</p>
      <input type="email" name="email" value="<?php echo $row['email']; ?>" required>

      <p>Mật khẩu mới (để trống nếu không đổi)</p>
      <input type="password" name="password">

      <p>Loại người dùng</p>
      <select name="user_type">
         <option value="user" <?php if($row['user_type']=='user') echo 'selected'; ?>>user</option>
         <option value="admin" <?php if($row['user_type']=='admin') echo 'selected'; ?>>admin</option>
      </select>

      <input type="submit" name="update_user" value="Cập nhật" class="btn">
      <a href="admin_users.php" class="option-btn">Hủy</a>
   </form>
</div>
<?php } ?>

=======
      <div class="table-responsive">
         <table class="user-table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Họ tên</th>
                  <th>Email</th>
                  <th>Loại tài khoản</th>
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
               $db->query("SELECT COUNT(*) as total FROM `users`");
               $row_count = $db->single();
               $total_pages = ceil($row_count['total'] / $limit);

               // Lấy dữ liệu
               $db->query("SELECT * FROM `users` LIMIT $offset, $limit");
               $select_users = $db->resultSet();

               if (count($select_users) > 0) {
                  foreach ($select_users as $fetch_users) {
               ?>
                     <tr>
                        <td><?php echo $fetch_users['id']; ?></td>
                        <td><?php echo htmlspecialchars($fetch_users['name']); ?></td>
                        <td><?php echo htmlspecialchars($fetch_users['email']); ?></td>
                        <td>
                           <?php if ($fetch_users['user_type'] == 'admin'): ?>
                              <span class="badge badge-admin">Quản trị viên</span>
                           <?php else: ?>
                              <span class="badge badge-user">Khách hàng</span>
                           <?php endif; ?>
                        </td>
                        <td>
                           <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');" class="action-btn">
                              <i class="fas fa-trash"></i> Xóa
                           </a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="5" style="text-align:center; padding:2rem;">Chưa có người dùng nào!</td></tr>';
               }
               ?>
            </tbody>
         </table>
>>>>>>> Stashed changes
      </div>

      <?php
      $base_url = 'admin_users.php';
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
