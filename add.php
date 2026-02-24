<?php
// ===============================
// 1️⃣ เชื่อมต่อฐานข้อมูล
// ===============================
$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "mythic_craft";
$port = 3307;

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");


// ===============================
// 2️⃣ ตรวจสอบการ Submit Form
// ===============================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าจาก form
    $name = $_POST["name"];
    $price = $_POST["price"];
    $currency = $_POST["currency"];
    $stock_status = $_POST["stock_status"];
    $purchase_limit = $_POST["purchase_limit"];

    // ===============================
    // 3️⃣ คำสั่ง INSERT เพิ่มข้อมูล
    // ===============================
    $sql = "INSERT INTO products 
            (name, price, currency, stock_status, purchase_limit, created_at)
            VALUES 
            ('$name', '$price', '$currency', '$stock_status', '$purchase_limit', NOW())";

    // สั่งให้ query ทำงาน
    if ($conn->query($sql) === TRUE) {

        // ถ้าบันทึกสำเร็จ → กลับหน้า index
        header("Location: index.php");
        exit();

    } else {

        // ถ้าเกิด error
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Mythic Craft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">
    <h2>➕ Add Product</h2>

    <!-- ฟอร์มส่งข้อมูลแบบ POST -->
    <form method="POST">

        <!-- ชื่อสินค้า -->
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- ราคา -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <!-- สกุลเงิน -->
        <div class="mb-3">
            <label class="form-label">Currency</label>
            <select name="currency" class="form-select">
                <option value="THB">THB</option>
                <option value="USD">USD</option>
            </select>
        </div>

        <!-- สถานะสินค้า -->
        <div class="mb-3">
            <label class="form-label">Stock Status</label>
            <select name="stock_status" class="form-select">
                <option value="available">Available</option>
                <option value="out_of_stock">Out of Stock</option>
                <option value="notify_me">Notify Me</option>
            </select>
        </div>

        <!-- จำกัดจำนวนซื้อ -->
        <div class="mb-3">
            <label class="form-label">Purchase Limit</label>
            <input type="number" name="purchase_limit" class="form-control" value="1">
        </div>

        <!-- ปุ่มบันทึก -->
        <button type="submit" class="btn btn-success">💾 Save</button>

        <!-- ปุ่มยกเลิก -->
        <a href="index.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

</body>
</html>