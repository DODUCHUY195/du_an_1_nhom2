<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="/assets/img/favicon.png" />

    <title><?= $title ?? "Dashboard" ?></title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Nucleo Icons -->
    <link href="/du_an_11/app/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/du_an_11/app/assets/css/nucleo-svg.css" rel="stylesheet" />

    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>

    <!-- Main Styling -->
    <link href="/du_an_11/app/assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>

<header class="fixed top-0 right-0 w-full h-14 bg-slate-800 text-black flex justify-end items-center px-6 shadow-md z-50">
    <?php if (isset($_SESSION['user'])): ?>
        <div class="flex items-center space-x-4">
            <span class="font-semibold">👋 Xin chào, <?= $_SESSION['user']['full_name'] ?></span>
            <span class="text-sm text-slate-300">(<?= $_SESSION['user']['role'] ?>)</span>
            <a href="<?= BASE_URL ?>index.php?route=/logout"
               class="bg-red-500 hover:bg-red-600 text-black font-bold py-1.5 px-3 rounded transition">
                🚪 Đăng xuất
            </a>
        </div>
    <?php else: ?>
        <a href="<?= BASE_URL ?>index.php?route=/login"
           class="bg-blue-500 hover:bg-blue-600 text-black font-bold py-1.5 px-3 rounded transition">
            Đăng nhập
        </a>
    <?php endif; ?>
</header>


<body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
<div class="relative w-full bg-blue-500 h-60 rounded-b-xl dark:hidden"></div>
