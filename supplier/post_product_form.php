<h2 class="text-3xl font-bold text-amber-500 mb-6">Post a new Product</h2>
<div class="bg-white p-6 rounded-lg shadow">
    <form id="productForm" class="space-y-6" enctype="multipart/form-data">
        <div>
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="product_name" class="w-full p-3 border border-gray-300 rounded" required>
        </div>
        <div>
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="product_description" class="w-full p-3 border border-gray-300 rounded" rows="4" required></textarea>
        </div>
        <div>
            <label class="block text-gray-700 font-semibold">Price</label>
            <input type="number" name="product_price" class="w-full p-3 border border-gray-300 rounded" required>
        </div>
        <div>
            <label class="block text-gray-700 font-semibold">Product Photos (Min. 3)</label>
            <input type="file" name="product_images[]" multiple accept="image/*" class="w-full p-3 border border-gray-300 rounded" id="imageInput" required max="3">
            <div id="imagePreview" class="mt-4 flex gap-2 flex-wrap"></div>
            <p id="imageError" class="text-red-500 text-sm mt-2 hidden">Please upload at least 3 images.</p>
        </div>
        <button type="submit" class="w-32 bg-amber-500 text-white p-3 rounded hover:bg-amber-600 transition">Post Product</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
