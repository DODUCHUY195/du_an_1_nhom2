<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative w-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="text-lg font-bold">Chi tiết khách hàng</h6>
          </div>

          <div class="px-6 py-6 space-y-2 text-sm">
            <p><strong>ID:</strong> <?= htmlspecialchars($user['user_id']) ?></p>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Activated:</strong> <?= $user['activated'] ? 'Yes' : 'No' ?></p>
          </div>

          <div class="px-6 pb-6 space-x-2">
            <a href="?route=/managerCustomer/edit&user_id=<?= $user['user_id'] ?>"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">Sửa</a>
            <a href="?route=/managerCustomer/toggle&user_id=<?= $user['user_id'] ?>"
               class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">
              <?= $user['activated'] ? 'Khóa' : 'Mở' ?>
            </a>
            <a href="?route=/managerCustomer/delete&user_id=<?= $user['user_id'] ?>"
               onclick="return confirm('Xóa khách hàng này?')"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Xóa</a>
            <a href="?route=/managerCustomer"
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded text-sm">Quay lại</a>
          </div>
        </div>
      </div>
    </div>
<?php require_once './views/layouts/admin/footer.php'; ?>