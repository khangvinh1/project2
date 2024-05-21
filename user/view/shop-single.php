<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shoppers &mdash; Product Detail</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="site-wrap">
        <div class="bg-light py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-0"><a href="index.html">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Shop Single</strong></div>
                </div>
            </div>
        </div>
        <div class="site-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if (isset($product[0]['img'])) {
                            echo '<img src="../../uploads/' . $product[0]['img'] . '" alt="Image" class="img-fluid">';
                        } else {
                            echo 'Không có hình ảnh';
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if (isset($product[0]['tensp'])) {
                            echo '<h2 class="text-black">' . $product[0]['tensp'] . '</h2>';
                        } else {
                            echo 'Không có tên sản phẩm';
                        }
                        ?>
                        <p><?php echo isset($product[0]['mota']) ? $product[0]['mota'] : 'Không có mô tả sản phẩm'; ?></p>
                        <p class="text-primary h4"><?php echo isset($product[0]['gia']) ? $product[0]['gia'] . ' VND' : 'Không có giá sản phẩm'; ?></p>
                        <div class="mb-5">
                            <div class="input-group mb-3" style="max-width: 120px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                                </div>
                                <input type="text" class="form-control text-center" value="1" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                                </div>
                            </div>
                        </div>
                        <p><a href="cart.html" class="buy-now btn btn-sm btn-primary">Add To Cart</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
