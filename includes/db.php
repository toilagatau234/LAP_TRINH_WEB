<?php
// Luôn include `config.php` từ thư mục gốc dự án.
// `__DIR__` trỏ tới thư mục `includes`, nên cần lên một cấp vào `../config.php`.
require_once __DIR__ . '/../config.php';

/**
 * Class Database
 *
 * Lớp tiện ích đơn giản để thao tác với cơ sở dữ liệu bằng PDO.
 * - Khởi tạo kết nối PDO dùng các hằng số trong `config.php`.
 * - Cung cấp phương thức chuẩn bị truy vấn, bind tham số, thực thi và lấy kết quả.
 *
 * Ghi chú: lớp này là wrapper nhẹ quanh PDO để thuận tiện sử dụng trong dự án.
 */
class Database
{
    // Thông tin kết nối lấy từ `config.php`
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    // PDO handle và statement hiện tại
    private $dbh;
    private $stmt;
    private $error;

    /**
     * Database constructor.
     * Tạo kết nối PDO với các tùy chọn chuẩn:
     * - persistent connection: true (giữ kết nối)
     * - ERRMODE: EXCEPTION (ném ngoại lệ khi có lỗi)
     * - DEFAULT_FETCH_MODE: FETCH_ASSOC (mảng kết hợp)
     */
    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';port=' . DB_PORT . ';dbname=' . $this->dbname . ';charset=utf8';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        try {
            // Tạo PDO instance
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Lưu lỗi để debug và in ra (có thể thay bằng logging trong production)
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Chuẩn bị một câu SQL (PDO::prepare)
     * @param string $sql
     */
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Bind giá trị cho tham số trong câu truy vấn đã chuẩn bị
     * - Tự động xác định kiểu dữ liệu nếu không truyền `$type`.
     * @param string|int $param Tên tham số (ví dụ ':id') hoặc vị trí
     * @param mixed $value Giá trị bind
     * @param int|null $type PDO::PARAM_*
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Thực thi statement đã chuẩn bị
     * @return bool Trả về true nếu thực thi thành công
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Lấy tất cả kết quả dưới dạng mảng (fetchAll)
     * @return array
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Lấy 1 dòng kết quả (fetch)
     * @return array|false
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Lấy số dòng bị ảnh hưởng/đã trả về
     * @return int
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
