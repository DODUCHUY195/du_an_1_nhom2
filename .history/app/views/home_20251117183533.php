<!-- header -->
<?php include './app/views/layouts/header.php' ?>
<!-- navbar -->
<?php include './app/views/layouts/navbar.php' ?>

<!-- sidebar -->
<?php include './app/views/layouts/sidebar.php' ?>

<main class="p-6">
    <h2 class="text-2xl font-bold mb-5">
        <?= $page_title ?? "Page Title" ?>
    </h2>

    <!-- NỘI DUNG TRANG -->
    <?= $content ?? "" ?>
</main>

<!-- footer -->
<?php include './app/views/layouts/footer.php' ?>