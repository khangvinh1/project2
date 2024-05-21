<?php
function getall_sanpham($iddm, $limit = 0) {
    $conn = connectdb(); // Kết nối cơ sở dữ liệu
    $sql = "SELECT * FROM tbl_sanpham WHERE 1"; // Sửa query
    if($iddm > 0){
        $sql .= " AND iddm=".$iddm;
    }
    $sql .= " ORDER BY id DESC"; // Thêm ORDER BY và giới hạn bản ghi nếu cần
    if($limit > 0) {
        $sql .= " LIMIT $limit";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    //tra ve 1 mang
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}


function getonesp($id){
    $conn = connectdb();
    $query = "SELECT * FROM tbl_sanpham WHERE id=".$id; // Sửa đổi theo tên bảng thực tế
    $stmt = $conn->prepare($query); // Chuẩn bị truy vấn
    $stmt->execute(); // Thực thi truy vấn
    $kq = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $kq;
}


    function get_sanpham_by_danhmuc($iddm) {
        $conn = connectdb();
        $sql = "SELECT * FROM tbl_sanpham WHERE iddm = ?"; // Truy vấn dựa trên ID danh mục
        $stmt = $conn->prepare($sql);
        $stmt->execute([$iddm]); // Thực hiện truy vấn với tham số
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách sản phẩm
    }
    

?>