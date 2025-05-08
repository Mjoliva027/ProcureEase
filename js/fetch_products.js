document.addEventListener('DOMContentLoaded', () => {
    const productList = document.getElementById('productList');
  
    fetch('../supplier/fetch_products.php')
      .then(response => response.json())
      .then(products => {
        productList.innerHTML = '';
  
        if (products.length === 0) {
          productList.innerHTML = '<p class="text-gray-600">No products found.</p>';
          return;
        }
  
        products.forEach(product => {
          const card = document.createElement('div');
          card.className = 'bg-white p-2 w-48 h-56 rounded-lg shadow hover:shadow-lg transition flex flex-col';
  
          const imageHTML = product.images.length
            ? `<img src="${product.images[0]}" alt="${product.product_name}" class="w-48 h-32 object-cover rounded self-center">`
            : '';
  
          card.innerHTML = `
            ${imageHTML}
            <div class="mt-2">
              <h3 class="text-md font-semibold truncate">${product.product_name}</h3>
              <p class="text-amber-500 font-bold">₱${product.product_price}</p>
              <button 
                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded text-sm w-full view-btn" 
                data-product='${JSON.stringify(product)}'>
                View
              </button>
            </div>
          `;
  
          productList.appendChild(card);
        });
  
        // Add click handlers for "View" buttons
        document.querySelectorAll('.view-btn').forEach(button => {
          button.addEventListener('click', () => {
            const product = JSON.parse(button.getAttribute('data-product'));
  
            alert(
              `Product: ${product.product_name}\n` +
              `Description: ${product.product_description}\n` +
              `Price: ₱${product.product_price}`
            );
          });
        });
      })
      .catch(err => {
        console.error('Error fetching products:', err);
        productList.innerHTML = '<p class="text-red-500">Failed to load products.</p>';
      });
  });
  