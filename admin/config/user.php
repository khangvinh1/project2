<?php
function checkuser($user, $pass) {
    $conn = connectdb();  // Kết nối cơ sở dữ liệu
    
    if(!$conn) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Tạo chuỗi băm MD5 từ mật khẩu đầu vào
    $hashed_pass = md5($pass);
     
    // Chuẩn bị và thực hiện truy vấn
    $stmt = $conn->prepare("SELECT role, pass FROM tbl_user WHERE user = ?");
    $stmt->execute([$user]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  // Lấy kết quả
    
    if ($result && $result['pass'] === $hashed_pass) {
        return $result['role'];  // Trả về vai trò nếu đúng
    }
    
    return -1;  // Trả về -1 nếu sai hoặc không tìm thấy
}

?>
