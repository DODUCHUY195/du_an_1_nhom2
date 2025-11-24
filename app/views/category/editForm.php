<?php require_once "./views/layouts/admin/header.php"; ?>
<?php require_once "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once "./views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <h2>Sửa danh mục</h2>
        <form action="<?= BASE_URL . '?route=/categories/postEdit' ?>" method="POST">
            <input type="hidden" name="category_id" value="<?= $danhMuc['category_id'] ?>">

            <div class="mb-4">
                <label for="category_name" class="block mb-1 font-semibold">Tên danh mục</label>
                <input id="category_name" name="category_name" type="text" placeholder="Nhập tên danh mục" required
                       class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80
                       text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300
                       bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500
                       focus:border-fuchsia-300 focus:outline-none" value="<?= $danhMuc['category_name'] ?>" />
            </div>

            <div class="mb-4">
                <label for="description" class="block mb-1 font-semibold">Mô tả</label>
                <textarea id="description" name="description" placeholder="Nhập mô tả"
                          class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80
                          text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300
                          bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500
                          focus:border-fuchsia-300 focus:outline-none"><?= $danhMuc['description'] ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                        class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle
                        transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85
                        hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Sửa danh mục</button>
            </div>
        </form>
    </div>
</main>

<?php require_once "./views/layouts/admin/footer.php"; ?>
