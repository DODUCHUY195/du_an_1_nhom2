<!-- header -->
<?php include './views/layouts/header.php' ?>
<!-- navbar -->
<?php include './views/layouts/navbar.php' ?>

<!-- sidebar -->
<?php include './views/layouts/sidebar.php' ?>

<main class="p-6">
    <h2 class="text-2xl font-bold mb-5">
        <?= $page_title ?? "Page Title" ?>
    </h2>

    <!-- NỘI DUNG TRANG -->
    <?= $content ?? "" ?>
</main>

<!-- footer -->
<?php include './views/layouts/footer.php' ?>