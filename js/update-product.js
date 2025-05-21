document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('editProductForm');
  
    // Use the global window.currentProductId directly
    // No local let currentProductId here
  
    form.addEventListener('submit', (e) => {
      e.preventDefault();
  
      const name = document.getElementById('modalName').value.trim();
      const description = document.getElementById('modalDescription').value.trim();
      const price = parseFloat(document.getElementById('modalPrice').value);
      const quantity = parseInt(document.getElementById('modalQuantity').value, 10);

      console.log("Submitted Product ID:", currentProductId);
console.log("name:", name);
console.log("description:", description);
console.log("price:", price, typeof price);
console.log("quantity:", quantity, typeof quantity);

      if (!window.currentProductId || !name || !description || isNaN(price) || isNaN(quantity)) {
        alert("Please complete all fields correctly.");
        return;
      }
  
      fetch('../supplier/update_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            product_id: currentProductId,  // number or string
            product_name: modalName.value,
            product_description: modalDescription.value,
            product_price: Number(modalPrice.value),
            quantity: Number(modalQuantity.value)
          }),
        })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("Product updated successfully.");
          location.reload(); // Refresh page or you could update UI dynamically
        } else {
          alert("Update failed: " + data.message);
        }
      })
      .catch(err => {
        console.error("Error:", err);
        alert("An error occurred while updating the product.");
      });
    });
  });
  