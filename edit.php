<?php
// ==============================
// 1️⃣ เชื่อมต่อฐานข้อมูล
// ==============================

$host = "127.0.0.1";
$user = "root";
$password = "";
$dbname = "mythic_craft";
$port = 3307;

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $password, $dbname, $port);

// ถ้าเชื่อมต่อไม่สำเร็จ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");


// ==============================
// 2️⃣ รับค่า ID จาก URL
// ==============================

// เช็คว่า id มีไหม และต้องเป็นตัวเลข
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}

// แปลงเป็น integer เพื่อความปลอดภัย
$id = (int) $_GET['id'];


// ==============================
// 3️⃣ ดึงข้อมูลสินค้าตาม ID
// ==============================

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id); // i = integer
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// ถ้าไม่พบสินค้า
if (!$product) {
    die("Product not found");
}


// ==============================
// 4️⃣ เมื่อกดปุ่ม Update (POST)
// ==============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าจาก form
    $name = $_POST["name"];
    $price = $_POST["price"];

    // เตรียมคำสั่ง UPDATE
    $update = $conn->prepare("
        UPDATE products 
        SET name=?, price=? 
        WHERE id=?
    ");

    // s = string
    // d = decimal/double
    // i = integer
    $update->bind_param("sdi", $name, $price, $id);
    $update->execute();

    // กลับไปหน้า index หลัง update เสร็จ
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">
    <h2>Edit Product</h2>

    <!-- form ส่งข้อมูลกลับมาหน้าเดียวกัน -->
    <form method="POST">

        <!-- ชื่อสินค้า -->
        <div class="mb-3">
            <label>Name</label>
            <input 
                type="text" 
                name="name" 
                value="<?= htmlspecialchars($product['name']); ?>" 
                class="form-control"
                required
            >
        </div>

        <!-- ราคา -->
        <div class="mb-3">
            <label>Price</label>
            <input 
                type="number" 
                step="0.01"
                name="price" 
                value="<?= $product['price']; ?>" 
                class="form-control"
                required
            >
        </div>

        <!-- ปุ่ม Update -->
        <button class="btn btn-primary">Update</button>

        <!-- ปุ่มกลับ -->
        <a href="index.php" class="btn btn-secondary">Back</a>

    </form>
</div>

</body>
</html>