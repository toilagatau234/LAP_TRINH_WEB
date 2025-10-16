<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookept | About</title>
   <meta name="description" content="Knowledge space for nerds. Search online books by subject and add them to your favorite cart">
   <meta name="keywords" content="php, sql, mysql, html, css, javascript, book">
   <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="styles/main.css">
   <link rel="stylesheet" href="styles/customers/about.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>about us</h3>
      <p> <a href="home.php">home</a> / about </p>
   </div>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>

         <div class="content">
            <h3>why choose us?</h3>
            <h6>A choice that makes the difference</h6>
            <p>&bull; Professional organization, while always adding value and active in solving customer problems.</p>
            <p>&bull; Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
            <a href="contact.php" class="btn">contact us</a>
         </div>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="content">
            <h3>how we work</h3>
            <p>&bull; Access new ways to increase customer visibility and brand value. As well as looking to make the most of advances in digitization and embracing customer technology platforms.</p>
            <p>&bull; Selecting teams for every project, to ensure each event captures the attention of the people with the most relevant skills. Access partnerships from around the world.</p>
            <a href="about.php" class="btn">read more</a>
         </div>
         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>
         <div class="content">
            <h3>about us</h3>
            <p>&bull; Massive business volume for suppliers with profitable contracts.</p>
            <a href="about.php" class="btn">read more</a>
         </div>
      </div>
   </section>

   <section class="reviews">
      <h1 class="title">client's reviews</h1>
      <div class="box-container">
         <div class="box">
            <div class="review-infor">
               <img src="./public/about/ricardo_milos_png_render_by_marcopolo157_dhbge8t-pre.png" alt="review_img_1">
               <h3>quanh</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
               </div>
            </div>
            <p>Wonderful book for kids. My grandkids love it.</p>
         </div>

         <div class="box">
            <div class="review-infor">
               <img src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/270012528_3144488045830078_518552516310324421_n.jpg?_nc_cat=110&ccb=1-6&_nc_sid=174925&_nc_ohc=SX8_BepqJZoAX-l8IIk&_nc_ht=scontent.fsgn2-6.fna&oh=00_AT9EpmidHI0EJRqtjNDGKO8SSpUplJKau7ZaUApP5JR7FA&oe=62897475" alt="review_img_2">
               <h3>tên vô</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
               </div>
            </div>
            <p>I read the whole thing on the exercise bike and couldn't put it down.</p>
         </div>

         <div class="box">
            <div class="review-infor">
               <img src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/270012528_3144488045830078_518552516310324421_n.jpg?_nc_cat=110&ccb=1-6&_nc_sid=174925&_nc_ohc=SX8_BepqJZoAX-l8IIk&_nc_ht=scontent.fsgn2-6.fna&oh=00_AT9EpmidHI0EJRqtjNDGKO8SSpUplJKau7ZaUApP5JR7FA&oe=62897475" alt="review_img_2">
               <h3>tên vô</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
               </div>
            </div>
            <p>Enjoyable, easy read. I smiled and hooked. Definitely read this one.</p>
         </div>

         <div class="box">
            <div class="review-infor">
               <img src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/270012528_3144488045830078_518552516310324421_n.jpg?_nc_cat=110&ccb=1-6&_nc_sid=174925&_nc_ohc=SX8_BepqJZoAX-l8IIk&_nc_ht=scontent.fsgn2-6.fna&oh=00_AT9EpmidHI0EJRqtjNDGKO8SSpUplJKau7ZaUApP5JR7FA&oe=62897475" alt="review_img_2">
               <h3>tê vô</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
               </div>
            </div>
            <p>Well worth the reader's time and a book to make you smile.</p>
         </div>

      </div>
   </section>

   <section class="authors">
      <h1 class="title">greate authors</h1>
      <div class="box-container">
         <div class="box">
            <img src="./public/about/z7101538677848_54b46b59ce93821b1c7e74a336444c55.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>Quốc Anh</h3>
         </div>

         <div class="box">
            <img src="images/author-2.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>tên vào</h3>
         </div>

         <div class="box">
            <img src="images/author-3.jpg" alt="">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3>tên vào</h3>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>