<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
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
      </div>

      <?php
      $base_url = 'admin_users.php';
      include 'components/pagination.php';
      ?>

   </section>
   <script src="js/admin_script.js"></script>
</body>

</html>