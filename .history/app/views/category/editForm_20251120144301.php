<?php require_once "./views/layouts/admin/header.php"; ?>

<?php require_once  "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

    <?php require_once  "./views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <h2>Sua danh muc</h2>
        <form action="<?= BASE_URL . '?route=/categories/postEdit' ?>" method="POST">
            <div class="mb-4">
                <input type="email" placeholder="" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
            </div>
            <div class="mb-4">
                <input type="password" placeholder="" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
            </div>

            <div class="text-center">
                <button type="button" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Sua danh muc</button>
            </div>
        </form>

</main>


<?php require_once  "./views/layouts/admin/footer.php"; ?>