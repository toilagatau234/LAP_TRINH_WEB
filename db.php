<?php

    require_once __DIR__ . 'config.php';

    class database {
        // thuooc tinh luu thong tin ket noi
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbname = DB_NAME;

        //thuộc tính PDO (PHP Data Object)
        private $pdo; // đối tượng PDO để quản lý kết nối cơ sở dữ liệu
        private $stmt; // đối tượng PDOStatement để chuẩn bị và thực thi câu lệnh
        private $error; // biến để lưu trữ thông báo lỗi

        //tự động chạy khi một đối tượng database mới được tạo

        public function __construct() {

            //chuỗi DNS (Data Source Name) xác định cách kết nối đến CSDL
            $dsn = 'mysql:host=' . $this->host . ';port' . DB_PORT . ';dbname=' . $this->dbname . ';charset=utf8';

            //các tuỳ chọn cho kết nối PDO
            $options = [

                PDO::ATTR_PERSISTENT => true, //giữ kết nối mở để tái sử dụng
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //báo lỗi dưới dạng ngoại lệ
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //lấy dữ liệu dưới dạng mảng kết hợp
            
            ];

            //sử dụng truy-catch để xử lý lỗi kết nối CSDL
            try {
                //tạo đối tượng PDO mới với các tham số đã cung cấp
                $this->pdo = new pdo($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $ex) {
                //nếu có lỗi, lưu thông báo lỗi vào biến $error
                $this->error = $ex->getMessage();
                
                error_log($this->error); //hiển thị lỗi (có thể ghi log thay vì hiển thị trong môi trường sản xuất)
                die('Database Connection Error'); //dừng thực thi và thông báo lỗi kết nối
            }
        }

        //nhân 1 câu lênhj SQL và chuẩn bị nó để thực thi
        public function query($sql) {
            $this->stmt = $this->pdo->prepare($sql);
        }
        
        //ngăn chặn SQL injection bằng cách ràng buộc giá trị an toàn
        public function bind($param, $value, $type = null) {
            //kiểm tra kiểu dữ liệu của gia strij nếu không được chỉ dịnh rox
            if (is_null($type)){
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
                        $type = PDO::PARAM_STR; // mặc định là chuỗi
                }
            }

            //gắn giá trị với tham số trong câu lệnh đã chuẩn bị
            $this->stmt->bindValue($param, $value, $type);
        }

        //hàm thực thi câu lệnh
        public function execute() {
            return $this->stmt-> execute();
        }

        //hàm lấy tất cả các kết quả
        public function resultSet() {
            $this->execute(); //thực thi lệnh
            return $this->stmt->fetchAll(); //trả vè một mảng chứa tất cả các bản ghi
        }

        //hàm lấy một bản ghi duy nhất
        public function single() {
            $this->execute(); //thực thi lệnh
            return $this->stmt->fetch(); //trả về một bản ghi duy nhất
        }

        //hàm đếm số bản ghi bị ảnh hưởng bởi câu lệnh
        public function rowCount() {
            return $this->stmt->rowCount();
        }

        //sử dụng lastInsertId() để lấy ID của bản ghi mới được chèn vào
        public function lastInsertId() {
            return $this->pdo->lastInsertId();
        }

        //các phương thức TRẤNCTION
        public function beginTransaction() {
            return $this->pdo->beginTransaction();
        }

        public function commit() {
            return $this->pdo->commit();
        }

        public function rollback() {
            return $this->pdo->rollback();
        }
    }    
?>