<?php
session_start();


$order_code = strtoupper(bin2hex(random_bytes(8)));


if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
} else {

    $cart = [];
}


$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Unknown';
$user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : 'Unknown';

$total_price = 0;
foreach ($cart as $item) {
    if (isset($item['price']) && isset($item['quantity'])) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link
      rel="shortcut icon"
      href="./assets/images/logo/favicon.jpeg"
      type="image/x-icon"
    />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniCart - eCommerce receipt Website</title>
    <link rel="stylesheet" href="/assets/css/receipt.css"> <!-- Link to your CSS file -->
    <style>
        .receipt-container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header h1 {
            margin: 0;
            color: #333;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-body {
            margin-bottom: 20px;
        }
        .receipt-body .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .receipt-body .item:last-child {
            border-bottom: none;
        }
        .receipt-body .item .item-name {
            font-weight: bold;
        }
        .receipt-footer {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
        .receipt-footer .thank-you {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p>Order #<?php echo htmlspecialchars($order_code); ?></p>
            <p>Date: <?php echo htmlspecialchars(date('Y-m-d')); ?></p>
        </div>
        <div class="receipt-body">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user_address); ?></p>

            <h2>Order Details</h2>
            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $item): ?>
                    <?php if (isset($item['name'], $item['price'], $item['quantity'])): ?>
                        <div class="item">
                            <span class="item-name"><?php echo htmlspecialchars($item['name']); ?> x <?php echo htmlspecialchars($item['quantity']); ?></span>
                            <span class="item-price">Rp <?php echo number_format($item['price'], 2, ',', '.'); ?></span>
                        </div>
                    <?php else: ?>
                        <p>Item data is missing.</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No items in cart.</p>
            <?php endif; ?>
        </div>
        <div class="receipt-footer">
            <p>Total: Rp <?php echo number_format($total_price, 2, ',', '.'); ?></p>
            <div class="thank-you">
                <p>Thank you for shopping with us!</p>
            </div>
            <div class="button">
            <a href="index.php">
  <button type="button" id="index-button">Kembali ke halaman</button>
</a>
                </div>
        </div>
    </div>
</body>
</html>
