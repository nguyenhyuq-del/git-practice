<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

extract((new AdminCatalogController())->addProduct(), EXTR_OVERWRITE);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm trang chủ</title>

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            font-family:Arial;
            background:#f4f6f8;
        }

        .layout{
            display:grid;
            grid-template-columns:260px 1fr;
            min-height:100vh;
        }

        .sidebar{
            background:#15985d;
            color:white;
            padding:25px 20px;
        }

        .sidebar h2{
            font-size:30px;
            margin-bottom:35px;
            line-height:1.25;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:14px 12px;
            border-radius:8px;
            margin-bottom:8px;
            font-weight:bold;
        }

        .sidebar a:hover,
        .sidebar a.active{
            background:rgba(255,255,255,.18);
        }

        .main{
            padding:30px;
        }

        .box{
            max-width:820px;
            background:white;
            padding:30px;
            border-radius:14px;
            box-shadow:0 4px 14px rgba(0,0,0,.08);
        }

        h1{
            font-size:34px;
            margin-bottom:20px;
        }

        label{
            display:block;
            font-weight:bold;
            margin-top:15px;
        }

        input,
        select,
        textarea{
            width:100%;
            margin-top:8px;
            padding:12px;
            border:1px solid #ddd;
            border-radius:8px;
            font-size:15px;
            font-family:Arial;
        }

        input,
        select{
            height:45px;
        }

        input[type="file"]{
            padding:10px 12px;
        }

        textarea{
            min-height:130px;
            resize:vertical;
            line-height:1.5;
        }

        .check{
            width:auto;
            height:auto;
            margin-right:6px;
        }

        button,
        .back{
            display:inline-block;
            background:#15985d;
            color:white;
            border:none;
            padding:13px 20px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
            cursor:pointer;
            margin-top:20px;
        }

        .back{
            background:#777;
            margin-left:8px;
        }

        .error{
            background:#ffe1e1;
            color:red;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
            font-weight:bold;
        }

        .note{
            background:#fff7d6;
            color:#8a5c00;
            padding:12px;
            border-radius:8px;
            margin:15px 0;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="layout">

    <?php
    $page = "products";
    include "sidebar.php";
    ?>

    <main class="main">

        <div class="box">

            <h1>Thêm sản phẩm trang chủ</h1>

            <?php if ($error) { ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <div class="note">
                HOT sẽ tự hiện khi Đã bán &gt; 500. Giảm giá tự tính từ Giá cũ và Giá mới.
            </div>

            <form method="POST" enctype="multipart/form-data">

                <label>Tên sản phẩm</label>
                <input type="text" name="name" required>

                <label>Danh mục</label>
                <select name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>

                    <?php foreach ($cats as $key => $label) { ?>
                        <option value="<?php echo htmlspecialchars($key); ?>">
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php } ?>
                </select>

                <label>Giá mới</label>
                <input type="number" name="price" required>

                <label>Giá cũ</label>
                <input type="number" name="old_price" value="0">

                <label>Ảnh sản phẩm</label>
                <input type="file" name="image" accept="image/*" required>

                <label>Đã bán</label>
                <input type="number" name="sold_count" value="0">

                <label>Số sao</label>
                <input type="number" name="star_count" value="5" min="1" max="5">

                <label>Số đánh giá</label>
                <input type="number" name="review_count" value="0">

                <label>Mô tả chi tiết</label>
                <textarea name="mo_ta_chi_tiet" placeholder="Nhập mô tả chi tiết sản phẩm..."></textarea>

                <label>
                    <input class="check" type="checkbox" name="is_new" value="1">
                    Sản phẩm mới / NEW
                </label>

                <label>
                    <input class="check" type="checkbox" name="is_flash_sale" value="1">
                    Flash sale
                </label>

                <button type="submit">Thêm sản phẩm</button>
                <a href="products.php" class="back">Quay lại</a>

            </form>

        </div>

    </main>

</div>

</body>
</html>