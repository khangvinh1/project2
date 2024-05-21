<?php
session_start(); // Bắt đầu session
include ("../config/config.php");
include ("../config/sanpham.php");
include ("../config/danhmuc.php");
$sphome1=getall_sanpham(0,0);
//controller
include "../view/header.php";
$action = isset($_GET["act"]) ? $_GET["act"] : 'home';
    switch ($action){
        case 'about':
                include "../view/about.php";
            break;
        case 'shop':
                $dsdm=getall_dm();
                if(isset($_GET['id'])&&($_GET['id']>0)){
                    $iddm=$_GET['id'];
                    $dssp=getall_sanpham($iddm,0);
                }
                else{
                    $dssp=getall_sanpham(0,0);
                }
                include "../view/shop.php";
            break;
            case 'product':
                if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                    $id = $_GET['id'];
                    $product = getonesp($id);
                    if (!$product) {
                        // Nếu không tìm thấy sản phẩm, chuyển hướng người dùng về trang chính
                        header("Location: index.php");
                        exit(); // Đảm bảo kết thúc luồng chương trình
                    }
                    include "../view/shop-single.php";
                } else {
                    header("Location: index.php");
                }
                break;
            
         case 'contact':
                include "../view/contact.php";
            break;
        case 'thoat':
            unset ($_SESSION['role']);
            header("Location: login.php");
            break;
        
        default:
        include "../view/home.php";
        break;
    }

 


include "../view/footer.php";

?>