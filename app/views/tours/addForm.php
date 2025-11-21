<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <h2>Thêm Tour</h2>
        <form action="<?= BASE_URL . '?route=/tours/postAdd' ?>" method="POST">

            <div class="mb-4">
                <label for="tour_name" class="block mb-1 font-semibold">Tên Tour</label>
                <input id="tour_name" type="text" name="tour_name" placeholder="Tên tour" required class="input-field"/>
            </div>

            <div class="mb-4">
                <label for="price" class="block mb-1 font-semibold">Giá</label>
                <input id="price" type="text" name="price" placeholder="Giá" required class="input-field"/>
            </div>

            <div class="mb-4">
                <label for="duration_days" class="block mb-1 font-semibold">Số Ngày</label>
                <input id="duration_days" type="text" name="duration_days" placeholder="Số ngày" required class="input-field"/>
            </div>

            <div class="mb-4">
                <label for="description" class="block mb-1 font-semibold">Mô Tả</label>
                <input id="description" type="text" name="description" placeholder="Mô tả" required class="input-field"/>
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-1 font-semibold">Trạng Thái</label>
                <input id="status" type="text" name="status" placeholder="Trạng thái" required class="input-field"/>
            </div>

            <div class="mb-4">
                <label for="created_at" class="block mb-1 font-semibold">Ngày Tạo</label>
                <input id="created_at" type="text" name="created_at" placeholder="Ngày tạo" required class="input-field"/>
            </div>

            <div class="text-center">
                <button type="submit" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Thêm Tour</button>
            </div>

        </form>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>
