<?php
include 'includes/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){ header('location:login.php'); };

$db = new Database();

if(isset($_GET['delete'])){// Xóa tin nhắn
   $delete_id = $_GET['delete'];// Lấy id cần xóa
   $db->query("DELETE FROM `message` WHERE id = :id");// Chuẩn bị câu truy vấn xóa
   $db->bind(':id', $delete_id);// Bind giá trị
   $db->execute();// Thực thi câu truy vấn
   header('location:admin_contacts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Admin | Messages</title>
   <link rel="icon" href="public/favicon.ico">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/users.css">
   <link rel="stylesheet" href="styles/pagination.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">
   <h1 class="title"> Tin nhắn </h1>
   <div class="box-container">
   <?php
      // --- LOGIC PHÂN TRANG ---
      $limit = 6; 
      $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;// Trang hiện tại
      if ($current_page < 1) $current_page = 1;// Đảm bảo trang hiện tại không nhỏ hơn 1
      $offset = ($current_page - 1) * $limit;

      // Đếm tổng
      $db->query("SELECT COUNT(*) as total FROM `message`");// Chuẩn bị câu truy vấn đếm
      $row_count = $db->single();// Lấy kết quả
      $total_pages = ceil($row_count['total'] / $limit);// Tính tổng số trang

      // Lấy dữ liệu
      $db->query("SELECT * FROM `message` LIMIT $offset, $limit");// Chuẩn bị câu truy vấn lấy dữ liệu
      $select_message = $db->resultSet();// Lấy kết quả

      if(count($select_message) > 0){
         foreach($select_message as $fetch_message){// Lặp qua từng tin nhắn
   ?>
   <div class="box">
      <p> ID User : <span><?php echo $fetch_message['user_id']; ?></span> </p>
      <p> Tên : <span><?php echo $fetch_message['name']; ?></span> </p>
      <p> SĐT : <span><?php echo $fetch_message['number']; ?></span> </p>
      <p> Email : <span><?php echo $fetch_message['email']; ?></span> </p>
      <p> Tin nhắn : <span><?php echo $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Xóa tin nhắn này?');" class="delete-btn">Xóa tin nhắn</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">Bạn không có tin nhắn nào!</p>';
   }
   ?>
   </div>

   <?php 
      $base_url = 'admin_contacts.php';
      include 'components/pagination.php'; 
   ?>

</section>
<script src="js/admin_script.js"></script>
</body>
</html>