<?php
function getall_sp() {
    $conn = connectdb();
    // Truy vấn để lấy tất cả danh mục
    $query = "SELECT * FROM tbl_sanpham"; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả
    return $kq; // Trả về kết quả
}

// Function to insert a product
function insert_sanpham($iddm, $tensp, $img, $gia) {
    $conn = connectdb();
    $sql = "INSERT INTO tbl_sanpham (iddm, tensp, img, gia) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$iddm, $tensp, $img, $gia]);
    return $conn->lastInsertId();
}


function getonesp($id){
    $conn = connectdb();
    $query = "SELECT * FROM tbl_sanpham WHERE id=".$id; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $kq;
}

function updatesp($id, $iddm, $tensp, $img, $gia) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu

    try {
        // Xây dựng câu lệnh SQL cho trường hợp có hình ảnh và không có hình ảnh
        if ($img != "") {
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensp = ?, img = ?, gia = ?
                    WHERE id = ?";
            $params = [$iddm, $tensp, $img, $gia,  $id];
        } else {
            $sql = "UPDATE tbl_sanpham 
                    SET iddm = ?, tensp = ?, gia = ?
                    WHERE id = ?";
            $params = [$iddm, $tensp, $gia, $id];
        }

        $stmt = $conn->prepare($sql); // Chuẩn bị câu lệnh SQL
        $stmt->execute($params); // Thực hiện truy vấn
        
        return true; // Trả về true nếu cập nhật thành công
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage(); // Thông báo lỗi nếu xảy ra lỗi
        return false; // Trả về false nếu cập nhật thất bại
    }
}

    function deletesp($id) {
        $conn = connectdb();
        $sql = "DELETE FROM tbl_sanpham WHERE id=".$id;
        $conn -> exec($sql);
    }


?>