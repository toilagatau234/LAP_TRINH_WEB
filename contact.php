<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['send'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if (mysqli_num_rows($select_message) > 0) {
      $message[] = 'message sent already!';
   } else {
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | Contact</title>
   <meta name="description" content="Knowledge space for nerds. Search online books by subject and add them to your favorite cart">
   <meta name="keywords" content="php, sql, mysql, html, css, javascript, book">
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
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
         <h3>&#9135;&#9135;&#9135;&#9135;&nbsp;&nbsp;nói điều gì đó!</h3>
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
            <a href="https://bookept.herokuapp.com">
               <img src="./public/contact/website.svg" alt="website">
            </a>
            <a href="">
               <img src="./public/contact/messenger.svg" alt="messenger">
            </a>
            <a href="">
               <img src="./public/contact/github.svg" alt="github">
            </a>
            <a href="mailto:ititiu19228@student.hcmiu.edu.vn">
               <img src="./public/contact/email.svg" alt="email">
            </a>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>