<?php
include 'includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) { header('location:login.php'); }

$db = new Database();

if (isset($_POST['send'])) { // Xử lý gửi tin nhắn
   $name = $_POST['name'];// Lấy tên người gửi
   $email = $_POST['email'];// Lấy email người gửi
   $number = $_POST['number'];// Lấy số điện thoại người gửi
   $msg = $_POST['message'];// Lấy nội dung tin nhắn

   $db->query("SELECT * FROM `message` WHERE name = :name AND email = :email AND number = :number AND message = :message");// Kiểm tra tin nhắn đã tồn tại chưa
   $db->bind(':name', $name);// Bind tên người gửi
   $db->bind(':email', $email);// Bind email người gửi
   $db->bind(':number', $number);// Bind số điện thoại người gửi
   $db->bind(':message', $msg);// Bind nội dung tin nhắn
   $db->execute();

   if ($db->rowCount() > 0) {
      $message[] = 'message sent already!';
   } else {
      $db->query("INSERT INTO `message`(user_id, name, email, number, message) VALUES(:user_id, :name, :email, :number, :message)");// Thêm tin nhắn mới vào CSDL
      $db->bind(':user_id', $user_id);// Bind id người dùng
      $db->bind(':name', $name);// Bind tên người gửi
      $db->bind(':email', $email);// Bind email người gửi
      $db->bind(':number', $number);// Bind số điện thoại người gửi
      $db->bind(':message', $msg);// Bind nội dung tin nhắn
      $db->execute();
      $message[] = 'message sent successfully!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Bookept | Contact</title>
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="styles/main.css">
</head>
<body>
   <?php include 'header.php'; ?>
   <div class="heading">
      <h3>liên hệ với chúng tôi</h3>
      <p><a href="home.php">Trang chủ</a> / liên hệ</p>
   </div>
   <section class="contact">
      <form action="" method="post">
         <h3>⎯⎯⎯⎯  nói điều gì đó!</h3>
         <input type="text" name="name" required placeholder="tên đầy đủ" class="box">
         <input type="email" name="email" required placeholder="email" class="box">
         <input type="number" name="number" required placeholder="số điện thoại" class="box">
         <textarea name="message" class="box" placeholder="nhập tin nhắn của bạn..." id="" cols="30" rows="10"></textarea>
         <input type="submit" value="📧 gửi tin nhắn" name="send" class="btn">
      </form>
      <div class="contact-info">
         <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2330.3366358435924!2d106.65318198007925!3d10.800323557567667!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175292976c117ad%3A0x5b3f38b21051f84!2zSOG7jWMgVmnhu4duIEjDoG5nIEtow7RuZyBWaeG7h3QgTmFtIENTMg!5e0!3m2!1svi!2sus!4v1760601215149!5m2!1svi!2sus" width="600" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div class="contact-infor-content">
            <div>
               <img src="./public/contact/location.svg" alt="address">
               <p>1 Cộng Hòa, Phường 4, Tân Bình, Thành phố Hồ Chí Minh, Việt Nam</p>
            </div>
            <div>
               <img src="./public/contact/phone.svg" alt="hotline">
               <p>Hotline: (+84) 123 456 789</p>
            </div>
         </div>
         <div class="contact-social">
            <a href="https://bookept.herokuapp.com"><img src="./public/contact/website.svg" alt="website"></a>
            <a href=""><img src="./public/contact/messenger.svg" alt="messenger"></a>
            <a href=""><img src="./public/contact/github.svg" alt="github"></a>
            <a href="mailto:ititiu19228@student.hcmiu.edu.vn"><img src="./public/contact/email.svg" alt="email"></a>
         </div>
      </div>
   </section>
   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html>