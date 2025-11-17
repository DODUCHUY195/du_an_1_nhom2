<aside id="sidebar"
    class="w-64 bg-white shadow-xl h-screen fixed top-0 left-0 pt-20 transition-all duration-300 overflow-y-auto">

    <ul class="px-4 space-y-2">
        <li>
            <a href="/dashboard"
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fas fa-home text-blue-500"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="/category"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fas fa-list text-green-500"></i> Category
            </a>
        </li>

        <li>
            <a href="/tour"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fas fa-map text-purple-500"></i> Tours
            </a>
        </li>

        <li>
            <a href="/booking"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fas fa-ticket-alt text-orange-500"></i> Booking
            </a>
        </li>

        <li>
            <a href="/users"
               class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fas fa-users text-indigo-500"></i> Users
            </a>
        </li>
    </ul>
</aside>

<!-- tránh bị đè layout -->
<div class="ml-64"></div>

<script>
document.getElementById("sidebarToggle").onclick = () => {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("ml-[-16rem]");
};
</script>
