<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Thêm Tour</h2>
            <a href="<?= BASE_URL . '?route=/tours' ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <form action="<?= BASE_URL . '?route=/tours/postAdd' ?>" method="POST" enctype="multipart/form-data">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tour_code" class="block mb-2 font-semibold">Mã Tour (Tự động tạo)</label>
                        <input id="tour_code" type="text" value="<?= $tourCode ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"/>
                        <input type="hidden" name="tour_code" value="<?= $tourCode ?>"/>
                    </div>

                    <div>
                        <label for="tour_name" class="block mb-2 font-semibold">Tên Tour</label>
                        <input id="tour_name" type="text" name="tour_name" placeholder="Tên tour" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label for="category_id" class="block mb-2 font-semibold">Danh mục</label>
                        <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="price" class="block mb-2 font-semibold">Giá</label>
                        <input id="price" type="text" name="price" placeholder="Giá" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label for="start_date" class="block mb-2 font-semibold">Ngày bắt đầu</label>
                        <input id="start_date" type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label for="end_date" class="block mb-2 font-semibold">Ngày kết thúc</label>
                        <input id="end_date" type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div>
                        <label for="status" class="block mb-2 font-semibold">Trạng Thái</label>
                        <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php 
                            $tourModel = new Tour();
                            $statusOptions = $tourModel->getStatusOptions();
                            foreach ($statusOptions as $statusValue => $statusLabel): ?>
                                <option value="<?= $statusValue ?>" <?= $statusValue == 'draft' ? 'selected' : '' ?>>
                                    <?= $statusLabel ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle"></i> 
                            <?php 
                            $tourModel = new Tour();
                            echo $tourModel->getStatusDescription('draft');
                            ?>
                        </div>
                    </div>
                    
                    <div>
                        <label for="image" class="block mb-2 font-semibold">Hình Ảnh</label>
                        <input id="image" type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 font-semibold">Mô Tả</label>
                        <textarea id="description" name="description" placeholder="Mô tả" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"></textarea>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                        Thêm Tour
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>