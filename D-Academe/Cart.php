<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="w-full bg-gray-100">
<section class="py-16 bg-gray-200">
    <div class="container mx-auto text-center">
        <h2 class="text-5xl font-semibold text-gray-900 mb-10">Your Cart</h2>
        <div id="cartItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10"></div>
        <p id="emptyCartMessage" class="text-xl text-gray-500 mt-10 hidden">Your cart is empty.</p>
    </div>
</section>

<script>
    // Load cart from local storage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Reference to the cart container
    const cartItemsContainer = document.getElementById('cartItems');
    const emptyCartMessage = document.getElementById('emptyCartMessage');

    // Render cart items
    function renderCartItems() {
        if (cart.length === 0) {
            emptyCartMessage.classList.remove('hidden');
            return;
        }

        cart.forEach((item, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = "bg-white rounded-lg shadow-md p-6 text-left";
            itemDiv.innerHTML = `
                <h3 class="text-2xl font-semibold text-gray-800">${item.name}</h3>
                <p class="text-xl font-bold text-green-600 mt-2">Tkn ${item.price}</p>
                <button onclick="removeFromCart(${index})" class="bg-red-500 text-white py-2 px-4 rounded-full mt-4 hover:bg-red-600 transition-colors duration-300">
                    Remove
                </button>
            `;
            cartItemsContainer.appendChild(itemDiv);
        });
    }

    // Remove item from cart
    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        alert('Item removed from cart!');
        location.reload();
    }

    renderCartItems();
</script>
</body>
</html>
