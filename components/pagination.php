<?php
/*
--- COMPONENT PHÂN TRANG (pagination.php) ---

Component này yêu cầu các biến sau phải được định nghĩa TRƯỚC KHI include:

1. $current_page: (Bắt buộc) Số trang hiện tại (lấy từ $_GET['page'])
2. $total_pages:  (Bắt buộc) Tổng số trang (tính toán từ COUNT(*) và $items_per_page)
3. $base_url:     (Bắt buộc) URL của trang sẽ gắn vào link (ví dụ: 'admin_products.php')

*/
?>

<?php if (isset($total_pages) && $total_pages > 1) : // Chỉ hiển thị nếu có nhiều hơn 1 trang ?>
   <div class="pagination">

      <?php if ($current_page > 1) : ?>
         <a href="<?php echo htmlspecialchars($base_url); ?>?page=<?php echo $current_page - 1; ?>">&laquo;</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
         <a href="<?php echo htmlspecialchars($base_url); ?>?page=<?php echo $i; ?>" 
            class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
            <?php echo $i; ?>
         </a>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages) : ?>
         <a href="<?php echo htmlspecialchars($base_url); ?>?page=<?php echo $current_page + 1; ?>">&raquo;</a>
      <?php endif; ?>

   </div>
<?php endif; ?>