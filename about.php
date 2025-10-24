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
      <h3>Thông tin</h3>
      <p> <a href="home.php">Trang chủ</a> / Thông tin </p>
   </div>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>

         <div class="content">
            <h3>tại sao chọn chúng tôi?</h3>
            <h6>Sự lựa chọn tạo nên sự khác biệt</h6>
            <p>&bull; Tổ chức chuyên nghiệp, luôn gia tăng giá trị và chủ động giải quyết các vấn đề của khách hàng.</p>
            <p>&bull; Lorem ipsum dolor, sit amet consectetur adipisising elit. Những trở ngại mà đối với bản thân nhỏ nhất nói lên các chức vụ của cơ thể thường là lý do nhưng để có được?</p>
            <a href="contact.php" class="btn">liên hệ với chúng tôi</a>
         </div>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="content">
            <h3>cách chúng tôi làm việc</h3>
            <p>&bull; Tiếp cận những phương thức mới để tăng cường khả năng hiển thị của khách hàng và giá trị thương hiệu. Đồng thời, tìm cách tận dụng tối đa những tiến bộ trong số hóa và áp dụng các nền tảng công nghệ khách hàng..</p>
            <p>&bull; Lựa chọn đội ngũ cho từng dự án để đảm bảo mỗi sự kiện đều thu hút được sự chú ý của những người có kỹ năng phù hợp nhất. Tiếp cận các đối tác từ khắp nơi trên thế giới.</p>
            <a href="about.php" class="btn">đọc thêm</a>
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
            <h3>về chúng tôi</h3>
            <p>&bull; Khối lượng kinh doanh lớn cho các nhà cung cấp có hợp đồng sinh lời.</p>
            <a href="about.php" class="btn">đọc thêm</a>
         </div>
      </div>
   </section>

   <section class="reviews">
      <h1 class="title">đánh giá của khách hàng</h1>
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
            <p>Cuốn sách tuyệt vời dành cho trẻ em. Cháu tôi rất thích nó.</p>
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
            <p>Tôi đã đọc toàn bộ cuốn sách này trên xe đạp tập thể dục và không thể đặt nó xuống.</p>
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
            <p>Thú vị, dễ đọc. Tôi đã mỉm cười và bị cuốn hút. Nhất định phải đọc cuốn này.</p>
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
            <p>Rất đáng để độc giả dành thời gian đọc và một cuốn sách khiến bạn mỉm cười.</p>
         </div>

      </div>
   </section>

   <section class="authors">
      <h1 class="title">những tác giả</h1>
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