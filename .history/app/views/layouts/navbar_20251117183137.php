<nav class="w-full bg-white shadow-md py-3 px-6 fixed top-0 left-0 z-50 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <button id="sidebarToggle" class="text-gray-600 text-xl">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="font-semibold text-lg">Admin Dashboard</h1>
    </div>

    <div class="flex items-center gap-5">
        <div class="text-gray-700">
            <?= $_SESSION['user_name'] ?? "Admin" ?>
        </div>
        <a href="/logout" class="text-red-500 hover:text-red-700 font-semibold">Logout</a>
    </div>
</nav>

<!-- offset để tránh navbar che nội dung -->
<div class="pt-20"></div>
