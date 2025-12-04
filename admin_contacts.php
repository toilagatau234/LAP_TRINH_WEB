<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){ header('location:login.php'); };

$db = new Database();

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $db->query("DELETE FROM `message` WHERE id = :id");
   $db->bind(':id', $delete_id);
   $db->execute();
   header('location:admin_contacts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin | Tin nhắn</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/contacts.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> TIN NHẮN LIÊN HỆ </h1>

   <div class="admin-card">
      <div class="card-header">
         <h3>Danh sách phản hồi khách hàng</h3>
      </div>

      <div class="card-body">
         <table class="custom-table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Thông tin người gửi</th>
                  <th>Nội dung tin nhắn</th>
                  <th style="text-align: center;">Hành động</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  // --- PHÂN TRANG ---
                  $limit = 8; 
                  $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  if ($current_page < 1) $current_page = 1;
                  $offset = ($current_page - 1) * $limit;

                  $db->query("SELECT COUNT(*) as total FROM `message`");
                  $row_count = $db->single();
                  $total_pages = ceil($row_count['total'] / $limit);

                  $db->query("SELECT * FROM `message` LIMIT $offset, $limit");
                  $select_message = $db->resultSet();

                  if(count($select_message) > 0){
                     foreach($select_message as $fetch_message){
               ?>
               <tr>
                  <td>#<?php echo $fetch_message['id']; ?></td>
                  
                  <td class="info-group">
                     <span style="font-weight:bold; color:var(--purple);">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($fetch_message['name']); ?>
                     </span>
                     <span><i class="fas fa-phone"></i> <?php echo $fetch_message['number']; ?></span>
                     <span><i class="fas fa-envelope"></i> <?php echo $fetch_message['email']; ?></span>
                     <?php if(isset($fetch_message['user_id'])): ?>
                        <small>(User ID: <?php echo $fetch_message['user_id']; ?>)</small>
                     <?php endif; ?>
                  </td>

                  <td>
                     <div class="message-content">
                        "<?php echo htmlspecialchars($fetch_message['message']); ?>"
                     </div>
                  </td>

                  <td style="text-align: center;">
                     <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Xóa tin nhắn này?');" class="btn-delete">
                        <i class="fas fa-trash"></i> Xóa
                     </a>
                  </td>
               </tr>
               <?php 
                     }
                  } else {
                     echo '<tr><td colspan="4" style="text-align:center; padding:2rem;">Không có tin nhắn nào!</td></tr>';
                  }
               ?>
            </tbody>
         </table>
      </div>
   </div>

   <?php 
      $base_url = 'admin_contacts.php';
      include 'components/pagination.php'; 
   ?>

</section>
<script src="js/admin_script.js"></script>
</body>
</html>