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

        <form action="<?= BASE_URL ?>?route=/tours/postEdit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">

            <!-- Basic Info Tab -->
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
                                <label class="block text-sm font-medium">Ảnh hiện tại</label>
                                <?php if (!empty($tour['image'])): ?>
                                    <img src="<?= BASE_URL ?>./<?= $tour['image'] ?>"
                                        alt="Ảnh HDV" class="w-20 h-20 object-cover rounded-full">
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Chưa có ảnh</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Chọn ảnh mới (nếu muốn thay)</label>
                                <input type="file" name="image" class="border rounded px-3 py-2 w-full">
                            </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 font-semibold">Mô Tả</label>
                        <textarea id="description" name="description" placeholder="Mô tả" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"><?= htmlspecialchars($tour['description']) ?></textarea>
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

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>