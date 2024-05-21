<?php
// Kiểm tra xem session đã được bắt đầu chưa trước khi gọi session_start()
if (session_status() === PHP_SESSION_NONE) {
  session_start(); // Bắt đầu session nếu chưa được kích hoạt
}
// Các cấu hình và tệp bao gồm cần thiết
include("../config/config.php");
include("../config/user.php");

$login_error = ""; // Biến báo lỗi cho đăng nhập
$signup_error = ""; // Biến báo lỗi cho đăng ký

// Xử lý đăng nhập
if (isset($_POST['dangnhap'])) {
    $user = trim($_POST['user']); // Tên người dùng
    $pass = trim($_POST['pass']); // Mật khẩu 

    $conn = connectdb(); // Kết nối cơ sở dữ liệu

    if ($conn) { // Kiểm tra nếu kết nối thành công
        $role = checkuser($user, $pass); // Kiểm tra vai trò người dùng
        
        if ($role == 1) { // Nếu vai trò là admin
            $_SESSION['role'] = $role; // Lưu vai trò vào session
            $_SESSION['user'] = $user; // Lưu tên người dùng vào session
            header('Location: index.php'); // Chuyển hướng đến trang admin
            exit; // Đảm bảo mã dừng sau khi chuyển hướng
        } else if($role == 0){
            $_SESSION['role'] = $role; // Lưu vai trò vào session
            $_SESSION['user'] = $user; // Lưu tên người dùng vào session
            header ('Location: ../../user/web/index.php');
            exit;
        } else if ($role == -1) {
            $login_error = "Username or password incorrect"; // Thông báo lỗi đăng nhập
        } else {
            $login_error = "Unknown error occurred"; // Lỗi không xác định
        }
    } else {
        $login_error = "Database connection error"; // Thông báo lỗi kết nối
    }
}


// Xử lý đăng ký
if (isset($_POST['dangki'])) {
    $username = trim($_POST['user']); // Tên người dùng
    $email = trim($_POST['email']); // Email
    $password = trim($_POST['pass']); // Mật khẩu
    $confirm_password = trim($_POST['confirm_pass']); // Xác nhận mật khẩu
    $terms = isset($_POST['terms']); // Kiểm tra điều khoản

    // Kiểm tra tính hợp lệ
    if (!$terms) {
        $signup_error = "Bạn phải đồng ý với điều khoản và điều kiện.";
    } elseif (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $signup_error = "Vui lòng điền tất cả các trường.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = "Email không hợp lệ.";
    } elseif ($password !== $confirm_password) {
        $signup_error = "Mật khẩu và xác nhận mật khẩu không khớp.";
    } else {
        $conn = connectdb(); // Kết nối cơ sở dữ liệu
    
        if ($conn) { // Kiểm tra nếu kết nối thành công
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_user WHERE user = ? OR email = ?");
            $check_stmt->execute([$username, $email]);
            $duplicate_count = $check_stmt->fetchColumn(); // Số lượng trùng lặp
            
            if ($duplicate_count > 0) {
                $signup_error = "Tên người dùng hoặc email đã tồn tại."; // Thông báo lỗi trùng lặp
            } else {
                // Băm mật khẩu bằng md5
                $hashed_pass = md5($password); // Mã hóa mật khẩu bằng md5

                try {
                    $stmt = $conn->prepare("INSERT INTO tbl_user (user, email, pass) VALUES (?, ?, ?)");
                    $result = $stmt->execute([$username, $email, $hashed_pass]); // Chèn người dùng mới
                    if ($result) {
                        header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
                        exit; // Dừng sau khi chuyển hướng
                    } else {
                        $signup_error = "Đăng ký thất bại, vui lòng thử lại.";
                    }
                } catch (PDOException $e) {
                    $signup_error = "Lỗi khi thêm người dùng vào cơ sở dữ liệu: " . $e->getMessage();
                }
            }
        } else {
            $signup_error = "Database connection error"; // Thông báo lỗi kết nối
        }
    }
}

ob_end_flush(); // Kết thúc bộ đệm đầu ra
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
        }

        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-wrapper {
            max-width: 300px;
            width: 100%;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .form-wrapper.active {
            display: block;
        }

        .input-group {
            position: relative;
            margin-bottom: 30px;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
        }

        .input-group label {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
            transition: 0.3s;
        }

        .input-group input:focus + label, 
        .input-group input:valid + label {
            top: 0;
            transform: translateY(-50%);
            font-size: 14px;
            color: #4285f4;
        }

        .btnLogin {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            background-color: #4285f4;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btnLogin:hover {
            background-color: #357ae8;
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
        }

        .form-footer p {
            margin-bottom: 0;
        }

        .form-footer a {
            color: #4285f4;
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #357ae8;
        }

        .form-footer .separator {
            margin: 0 10px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-wrapper sign-in active">
        <h2 class="text-center mb-4">Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <div class="input-group">
                <input type="text" id="user" name="user" required>
                <label for="user">Username</label>
            </div>

            <div class="input-group">
                <input type="password" id="pass" name="pass" required>
                <label for="pass">Password</label>
            </div>

            <?php
            if (!empty($login_error)) { // Hiển thị thông báo lỗi đăng nhập
                echo "<span style='color: red; display: block; margin-bottom: 10px; text-align: center;'>" . $login_error . "</span>";
            }
            ?>

            <input type="submit" name="dangnhap" value="Login" class="btnLogin">

            <div class="form-footer">
                <p>Not a member? <a href="#" class="signUpBtn-link">Register</a></p>
            </div>
        </form>
    </div>

    <div class="form-wrapper sign-up">
        <h2 class="text-center mb-4">Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <div class="input-group">
                <input type="text" id="user" name="user" required>
                <label for="user">Username</label>
            </div>

            <div class="input-group">
                <input type="email" id="email" name="email" required>
                <label for="email">Email</label>
            </div>

            <div class="input-group">
                <input type="password" id="pass" name="pass" required>
                <label for="pass">Password</label>
            </div>

            <div class="input-group">
                <input type="password" id="confirm_pass" name="confirm_pass" required>
                <label for="confirm_pass">Confirm Password</label>
            </div>

            <div class="remember">
                <label><input type="checkbox" name="terms" required> I agree to the terms & conditions</label>
            </div>

            <?php
            if (!empty($signup_error)) { // Hiển thị thông báo lỗi đăng ký
                echo "<span style='color: red; display: block; margin-bottom: 10px; text-align: center;'>" . $signup_error . "</span>";
            }
            ?>

            <input type="submit" name="dangki" value="Signup" class="btnLogin">

            <div class="form-footer">
                <p>Already have an account? <a href="#" class="signInBtn-link">Sign In</a></p>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript để chuyển đổi giữa đăng nhập và đăng ký -->
<script>
    const signInBtnLink = document.querySelector('.signInBtn-link');
    const signUpBtnLink = document.querySelector('.signUpBtn-link');
    const signInForm = document.querySelector('.sign-in');
    const signUpForm = document.querySelector('.sign-up');

    signUpBtnLink.addEventListener('click', () => {
        signInForm.classList.remove('active');
        signUpForm.classList.add('active');
    });

    signInBtnLink.addEventListener('click', () => {
        signInForm.classList.add('active');
        signUpForm.classList.remove('active');
    });
</script>

<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
</body>
</html>
