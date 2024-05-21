<?php
session_start(); // Bắt đầu session
ob_start(); // Bắt đầu bộ đệm đầu ra
if(isset($_SESSION["role"]) && ($_SESSION["role"]==1)) {
// Bao gồm các cấu hình và tệp cần thiết
include("../config/config.php");
include("../config/danhmuc.php");
include("../config/sanpham.php");
//controller
include "../model/header.php";
if(isset($_GET['act'])){
    $act=$_GET['act'];
    switch ($act){
        case 'danhmuc':
            $kq = getall_dm(); // Gọi hàm lấy tất cả danh mục
            include('../model/danhmuc.php'); // Bao gồm tệp hiển thị danh mục
            break;

            break;
        case 'adddm':
           if(isset($_POST['submit'])&&($_POST['submit'])){
            $tendm=$_POST['tendm'];
            themdm($tendm);
           }
            $kq = getall_dm(); // Gọi hàm lấy tất cả danh mục
            include('../model/danhmuc.php'); // Bao gồm tệp hiển thị danh mục
            break;
    
                break;
        case 'deletedm':
            if(isset($_GET['id'])){
                $id=$_GET['id'];
                deldm($id);
            }
             $kq = getall_dm();  // Gọi hàm lấy tất cả danh mục
            include('../model/danhmuc.php');  //sau khi xóa trả lại trang dm
            
             break;
        case 'updatedm':
            // Khởi tạo biến $kqone mặc định là null
                $kqone = null;

            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $kqone = getonedm($id); 
                }
            if(isset($_POST['id']) && isset($_POST['tendm'])){
                $id = $_POST['id'];
                $newValue = $_POST['tendm'];
                update_dm($id, $newValue); 
                }
                $kq = getall_dm(); 
                include "../model/updatedmform.php";
                break;
            
        case 'sanpham':
                // Load danh sách danh mục
                $dsdm = getall_dm();
                // Load danh sách sản phẩm
                $kq = getall_sp();
                include "../model/sanpham.php"; 
                break;
        case 'updatesp':
                // Load danh sách danh mục
                $dsdm = getall_dm();
                //sp chi tiết theo id
                if(isset($_GET['id'])&&($_GET['id']>0)){
                    $spct=getonesp($_GET['id']);
                }
                // Load danh sách sản phẩm
                $kq = getall_sp();
                include "../model/updatesp.php"; 
                break;
        case 'sanpham_update':
            // Load danh sách danh mục
            $dsdm = getall_dm();
            //cập nhật sản phẩm
            if(isset($_POST['capnhat'])&&($_POST['capnhat'])){
                $iddm = $_POST['iddm'];
                $tensp = $_POST['tensp'];
                $gia = $_POST['gia'];
                $id = $_POST['id'];
               if ($_FILES['img']['name'] != "") {
                            $img = $_FILES['img']['name'];
                            $target_dir = "../../uploads/";
                            $target_file = $target_dir . basename($img);
                
                            // Định nghĩa loại tệp ảnh
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                            // Kiểm tra đuôi tệp ảnh hợp lệ
                            $valid_extensions = array("jpg", "jpeg", "png", "gif");
                            if (!in_array($imageFileType, $valid_extensions)) {
                                echo "Chỉ tiếp nhận các tệp có đuôi jpg, jpeg, png, gif.";
                                $uploadOk = 0; // Đuôi tệp không hợp lệ
                            } else {
                                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                                if (!file_exists($target_dir)) {
                                    mkdir($target_dir, 0777, true);
                                }
                
                                // Di chuyển tệp ảnh vào thư mục uploads
                                if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                                    echo "File đã được tải lên thành công.";
                                } else {
                                    echo "Không thể tải lên file.";
                                    echo "Lỗi: " . $_FILES['img']['error'];
                                    $uploadOk = 0; // Lỗi khi tải lên tệp
                                }
                            }
                        }
                        else{
                            $img = "";
                        }
                
                        // // Kiểm tra giá trị của $iddm và biến $uploadOk
                        // if ($iddm != '0' && $uploadOk == 1) {
                        //     // Gọi hàm để chèn sản phẩm mới vào cơ sở dữ liệu
                        //     insert_sanpham($iddm, $tensp, $img, $gia);
                        // } else {
                        //     if ($iddm == '0') {
                        //         echo "Vui lòng chọn danh mục hợp lệ.";
                        //     }
                        // }
                        updatesp($id, $iddm, $tensp, $img, $gia) ;   
            }

            // Load danh sách sản phẩm
            $kq = getall_sp();
            include "../model/sanpham.php"; 
                break;
        case 'sanpham_add':
                    if (isset($_POST['submit']) && $_POST['submit']) {
                        $iddm = $_POST['iddm'];
                        $tensp = $_POST['tensp'];
                        $gia = $_POST['gia'];
                        $img = "";
                        $uploadOk = 1; // Khởi tạo biến kiểm tra
                
                        if ($_FILES['img']['name'] != "") {
                            $img = $_FILES['img']['name'];
                            $target_dir = "../../uploads/";
                            $target_file = $target_dir . basename($img);
                
                            // Định nghĩa loại tệp ảnh
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                            // Kiểm tra đuôi tệp ảnh hợp lệ
                            $valid_extensions = array("jpg", "jpeg", "png", "gif");
                            if (!in_array($imageFileType, $valid_extensions)) {
                                echo "Chỉ tiếp nhận các tệp có đuôi jpg, jpeg, png, gif.";
                                $uploadOk = 0; // Đuôi tệp không hợp lệ
                            } else {
                                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                                if (!file_exists($target_dir)) {
                                    mkdir($target_dir, 0777, true);
                                }
                
                                // Di chuyển tệp ảnh vào thư mục uploads
                                if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                                    echo "File đã được tải lên thành công.";
                                } else {
                                    echo "Không thể tải lên file.";
                                    echo "Lỗi: " . $_FILES['img']['error'];
                                    $uploadOk = 0; // Lỗi khi tải lên tệp
                                }
                            }
                        }
                
                        // Kiểm tra giá trị của $iddm và biến $uploadOk
                        if ($iddm != '0' && $uploadOk == 1) {
                            // Gọi hàm để chèn sản phẩm mới vào cơ sở dữ liệu
                            insert_sanpham($iddm, $tensp, $img, $gia);
                        } else {
                            if ($iddm == '0') {
                                echo "Vui lòng chọn danh mục hợp lệ.";
                            }
                        }
                    }
                
                    // Load danh sách danh mục
                    $dsdm = getall_dm();
                    // Load danh sách sản phẩm
                    $kq = getall_sp();
                    include "../model/sanpham.php"; 
                    break;
                
        case 'deletesp':
                        if(isset($_GET['id'])){
                            $id=$_GET['id'];
                            deletesp($id);
                        }
                         $kq = getall_sp();  // Gọi hàm lấy tất cả danh mục
                        include('../model/sanpham.php');  //sau khi xóa trả lại trang sanpham
                        
            break;
                
                
        case 'thoat':
            unset ($_SESSION['role']);
            header("Location: login.php");
            break;
        
        default:
        include "../model/home.php";
        break;
    }
} else{
    include "../model/home.php";
}

include "../model/footer.php";
}
else {
    header("Location: login.php");
exit(); // Dừng mã sau khi chuyển hướng
}
ob_end_flush(); // Kết thúc bộ đệm đầu ra và gửi dữ liệu ra
?>