<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Sửa Tour</h2>
            <a href="<?= BASE_URL ?>?route=/tours"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <button class="tab-button inline-block p-4 rounded-t-lg border-b-2 text-blue-600 border-blue-600 active" data-tab="basic">
                        Thông tin cơ bản
                    </button>
                </li>
                <li class="mr-2">
                    <button class="tab-button inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" data-tab="pricing">
                        Giá theo mùa
                    </button>
                </li>
            </ul>
        </div>

        <form action="<?= BASE_URL ?>?route=/tours/postEdit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Basic Info Tab -->
                <div id="basic" class="tab-pane active">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Thông tin cơ bản</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tour_code" class="block mb-2 font-semibold">Mã Tour</label>
                                <input id="tour_code" type="text" value="<?= $tour['tour_code'] ?>" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" />
                            </div>

                            <div>
                                <label for="tour_name" class="block mb-2 font-semibold">Tên Tour</label>
                                <input id="tour_name" type="text" name="tour_name" value="<?= htmlspecialchars($tour['tour_name']) ?>" placeholder="Tên tour" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label for="category_id" class="block mb-2 font-semibold">Danh mục</label>
                                <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['category_id'] ?>" <?= $tour['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['category_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="price" class="block mb-2 font-semibold">Giá mặc định</label>
                                <input id="price" type="number" step="0.01" name="price" value="<?= $tour['price'] ?>" placeholder="Giá" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label for="start_date" class="block mb-2 font-semibold">Ngày bắt đầu</label>
                                <input id="start_date" type="date" name="start_date" value="<?= $tour['start_date'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label for="end_date" class="block mb-2 font-semibold">Ngày kết thúc</label>
                                <input id="end_date" type="date" name="end_date" value="<?= $tour['end_date'] ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label for="status" class="block mb-2 font-semibold">Trạng Thái</label>
                                <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php
                                    $tourModel = new Tour();
                                    $statusOptions = $tourModel->getStatusOptions();
                                    foreach ($statusOptions as $statusValue => $statusLabel): ?>
                                        <option value="<?= $statusValue ?>" <?= $tour['status'] == $statusValue ? 'selected' : '' ?>>
                                            <?= $statusLabel ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-info-circle"></i>
                                    <?php
                                    $tourModel = new Tour();
                                    echo $tourModel->getStatusDescription($tour['status']);
                                    ?>
                                </div>
                            </div>

                            <div>
                                <label for="created_at" class="block mb-2 font-semibold">Ngày Tạo</label>
                                <input id="created_at" type="text" value="<?= date('d/m/Y H:i', strtotime($tour['created_at'])) ?>" placeholder="Ngày tạo" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" />
                            </div>

                            <div>
                                <label for="image" class="block mb-2 font-semibold">Hình Ảnh Chính</label>
                                <?php if (!empty($tour['image'])): ?>
                                    <img src="<?= BASE_URL ?>../<?= $tour['image'] ?>" alt="Tour Image" class="mb-2 w-32 h-32 object-cover rounded">
                                <?php endif; ?>
                                <input type="hidden" name="current_image" value="<?= $tour['image'] ?>">
                                <input id="image" type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block mb-2 font-semibold">Mô Tả</label>
                                <textarea id="description" name="description" placeholder="Mô tả" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"><?= htmlspecialchars($tour['description']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Tab -->
                <div id="pricing" class="tab-pane hidden">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Giá theo mùa</h3>

                        <!-- Existing Prices -->
                        <?php if (!empty($prices)): ?>
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-3">Giá đã có</h4>
                                <?php foreach ($prices as $index => $price): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                        <input type="hidden" name="prices[<?= $price['price_id'] ?>][price_id]" value="<?= $price['price_id'] ?>">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block mb-2 font-semibold">Mùa</label>
                                                <input type="text" name="prices[<?= $price['price_id'] ?>][season]" value="<?= htmlspecialchars($price['season']) ?>" placeholder="Mùa (ví dụ: Mùa hè)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                            <div>
                                                <label class="block mb-2 font-semibold">Giá</label>
                                                <input type="number" step="0.01" name="prices[<?= $price['price_id'] ?>][price]" value="<?= $price['price'] ?>" placeholder="Giá" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                            <div>
                                                <label class="block mb-2 font-semibold">Ngày bắt đầu</label>
                                                <input type="date" name="prices[<?= $price['price_id'] ?>][start_date]" value="<?= $price['start_date'] ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                            <div>
                                                <label class="block mb-2 font-semibold">Ngày kết thúc</label>
                                                <input type="date" name="prices[<?= $price['price_id'] ?>][end_date]" value="<?= $price['end_date'] ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- New Prices -->
                        <div id="new-prices-container">
                            <h4 class="text-lg font-semibold mb-3">Thêm giá mới</h4>
                            <div class="border border-gray-200 rounded-lg p-4 mb-4 new-price-item">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block mb-2 font-semibold">Mùa</label>
                                        <input type="text" name="new_prices[0][season]" placeholder="Mùa (ví dụ: Mùa hè)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-semibold">Giá</label>
                                        <input type="number" step="0.01" name="new_prices[0][price]" placeholder="Giá" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-semibold">Ngày bắt đầu</label>
                                        <input type="date" name="new_prices[0][start_date]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block mb-2 font-semibold">Ngày kết thúc</label>
                                        <input type="date" name="new_prices[0][end_date]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-price-btn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Thêm giá mới
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                    Cập nhật Tour
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-blue-600', 'active');
                    btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });

                tabPanes.forEach(pane => {
                    pane.classList.add('hidden');
                });

                // Add active class to clicked button
                this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                this.classList.add('text-blue-600', 'border-blue-600', 'active');

                // Show target pane
                document.getElementById(targetTab).classList.remove('hidden');
            });
        });

        // Add price button functionality
        let priceIndex = 1;
        document.getElementById('add-price-btn').addEventListener('click', function() {
            const container = document.getElementById('new-prices-container');
            const newItem = document.createElement('div');
            newItem.className = 'border border-gray-200 rounded-lg p-4 mb-4 new-price-item';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold">Mùa</label>
                        <input type="text" name="new_prices[${priceIndex}][season]" placeholder="Mùa (ví dụ: Mùa hè)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold">Giá</label>
                        <input type="number" step="0.01" name="new_prices[${priceIndex}][price]" placeholder="Giá" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold">Ngày bắt đầu</label>
                        <input type="date" name="new_prices[${priceIndex}][start_date]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold">Ngày kết thúc</label>
                        <input type="date" name="new_prices[${priceIndex}][end_date]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            priceIndex++;
        });
    });
</script>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>