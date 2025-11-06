<?php
require_once 'config.php';

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}

// Thêm thể loại
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            header('Location: admin_categories.php');
            exit;
        } else {
            echo "Lỗi khi thêm thể loại: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Xóa thể loại
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: admin_categories.php');
        exit;
    } else {
        echo "Lỗi khi xóa thể loại: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thể loại</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="category">
    <h1 class="title">Quản lý thể loại</h1>

    <form action="" method="post">
        <input type="text" name="name" placeholder="Tên thể loại" required>
        <input type="submit" name="add_category" value="Thêm thể loại" class="btn">
    </form>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên thể loại</th>
            <th>Thao tác</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>
                            <a href='admin_categories.php?delete={$row['id']}' onclick=\"return confirm('Xóa thể loại này?');\">Xóa</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Chưa có thể loại nào.</td></tr>";
        }
        ?>
    </table>
</section>

</body>
</html>
