<?php
session_start();


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if cart data is posted
    if (isset($_POST['cart'])) {
        $_SESSION['cart'] = json_decode($_POST['cart'], true);
        $_SESSION['user_name'] = $_POST['name'];
        $_SESSION['user_address'] = $_POST['address'];
        $_SESSION['payment_method'] = $_POST['payment_method'];
        
        // Redirect to receipt page
        header('Location: receipt.php');
        exit();
    } else {
        echo "Cart data not found.";
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
    <title>UniCart - eCommerce Checkout Website</title>
    <link rel="stylesheet" href="assets/css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .checkout-container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }
        .cart-items {
            margin-bottom: 20px;
        }
        .cart-items h2 {
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .cart-item-content {
            flex-grow: 1;
        }
        .btn-action {
            background: none;
            border: none;
            color: #f00;
            font-size: 1.2em;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-action i {
            font-size: 1em;
        }
        .total-price {
            font-weight: bold;
        }
        .billing-info {
            margin-top: 20px;
        }
        .billing-info h2 {
            margin-bottom: 10px;
        }
        .checkout-actions {
            margin-top: 20px;
        }
        .checkout-actions button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>

        <div class="cart-items">
            <h2>Keranjang Anda</h2>
            <div id="cart-items-list"></div>
            <div class="total-price">
                <h3>Total Harga: Rp <span id="total-price">0.00</span></h3>
            </div>
        </div>

        <div class="billing-info">
            <h2>Informasi Pembayaran</h2>
            <form id="checkout-form" method="POST" action="">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" required>

                <label for="address">Alamat Pengiriman</label>
                <textarea id="address" name="address" required></textarea>

                <label for="payment_method">Metode Pembayaran</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Transfer Bank</option>
                </select>

                <input type="hidden" id="cart-data" name="cart" value="">

                <div class="checkout-actions">
                    <button type="submit" id="checkout-button">Selesaikan Pembayaran</button>
                </div>
                <a href="index.php">
  <button type="button" id="index-button">Kembali ke halaman</button>
</a>
            </form>
        </div>
    </div>

    <script>
        // Function to update cart display
        function updateCartDisplay() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalPrice = 0;
            const cartItemsList = document.getElementById('cart-items-list');
            const totalPriceElement = document.getElementById('total-price');
            cartItemsList.innerHTML = '';

            cart.forEach((item, index) => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                cartItem.innerHTML = `
                    <div class="cart-item-content">
                        <p>${item.name} x ${item.quantity}</p>
                        <p>Rp ${item.price.toLocaleString()}</p>
                    </div>
                    <button class="btn-action" onclick="removeFromCart(${index})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                cartItemsList.appendChild(cartItem);
                totalPrice += item.price * item.quantity;
            });

            totalPriceElement.textContent = totalPrice.toLocaleString();
        }

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
        }

        document.getElementById('checkout-form').addEventListener('submit', function(event) {
            event.preventDefault();

            // Validate the form
            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const paymentMethod = document.getElementById('payment_method').value;

            if (!name || !address || !paymentMethod) {
                alert('Harap lengkapi semua informasi.');
                return;
            }

            // Retrieve cart data from localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                alert('No items in cart.');
                return;
            }

            // Populate hidden field with cart data
            document.getElementById('cart-data').value = JSON.stringify(cart);

            // Submit the form
            this.submit();
        });

        // Display cart items on page load
        updateCartDisplay();
    </script>
</body>
</html>
