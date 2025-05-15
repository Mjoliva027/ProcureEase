<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<h2 class="text-3xl font-bold mb-6 text-amber-500">Your Posted Products</h2>
<div id="productList" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
    
  <!-- Products will be inserted here dynamically -->
  
</div>
<script src="../js/fetch_products.js"></script>
 <!-- Modal for product details -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg relative">
    <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
    
    <!-- Swiper carousel inside the modal -->
    <div class="swiper w-full h-64 md:h-80 lg:h-96 mb-4 rounded" id="modalSwiper">
      <div class="swiper-wrapper" id="modalSwiperWrapper">
        <!-- Images will be injected here -->
      </div>

      <!-- Swiper navigation -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-pagination"></div>
    </div>

    <h2 id="modalName" class="text-2xl font-bold mb-2"></h2>
    <p id="modalDescription" class="text-gray-600 mb-2"></p>
    <p id="modalPrice" class="text-amber-500 font-bold mb-2 text-lg"></p>
    <p id="modalQuantity" class="text-gray-800 font-semibold mb-2"></p>
  </div>
</div>

