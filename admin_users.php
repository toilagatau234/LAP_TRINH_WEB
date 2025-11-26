<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
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

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin | Users</title>
   <link rel="icon" href="public/favicon.ico">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="styles/admin.css">
   <link rel="stylesheet" href="styles/admin/users.css" class="css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title"> tài khoản người dùng </h1>

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

      </div>

      <?php
         };
      ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
