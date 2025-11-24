
<?php require_once "./views/layouts/header.php"; ?>

<?php require_once  "./views/layouts/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

    <?php require_once  "./views/layouts/navbar.php"; ?>

    <div class="w-full px-6 py-6 mx-auto">
        <?= $content ?>
    </div>

</main>

<?php require_once  "./views/layouts/footer.php"; ?>
