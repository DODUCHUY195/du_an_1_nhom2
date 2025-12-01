<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 mb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">Danh sách Hướng dẫn viên</h6>
            <a href="<?= BASE_URL ?>?route=/guides/create"
              class="px-2 py-2 bg-green-600 hover:bg-green-700 text-black rounded text-sm">
              + Tạo hồ sơ HDV
            </a>
          </div>

          <div class="overflow-x-auto px-4 pb-4">
            <table class="w-full table-auto text-left border-collapse text-slate-500">
              <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                  <th class="px-4 py-2 text-center">Mã HDV</th>
                  <th class="px-4 py-2 text-center">Ảnh</th>
                  <th class="px-4 py-2 text-center">Họ tên</th>
                  <th class="px-4 py-2 text-center">Ngày sinh</th>
                  <th class="px-4 py-2 text-center">Liên hệ</th>
                  <th class="px-4 py-2 text-center">Chứng chỉ</th>
                  <th class="px-4 py-2 text-center">Tour đang phụ trách</th>
                  <th class="px-4 py-2 text-center">Thao tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php if (!empty($guides)): foreach ($guides as $g): ?>
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-center"><?= htmlspecialchars($g['guide_id']) ?></td>
                      <td class="text-center">
                        <?php if (!empty($g['photo'])): ?>
                          <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($g['photo']) ?>"
                            alt="Ảnh HDV" class="w-16 h-16 object-cover rounded-full mx-auto">
                        <?php else: ?>
                          <span class="text-gray-400 italic">Chưa có ảnh</span>
                        <?php endif; ?>
                      </td>
                      <td class="px-4 py-2 text-center"><?= htmlspecialchars($g['full_name']) ?></td>
                      <td class="px-4 py-2 text-center"><?= htmlspecialchars($g['birth_date']) ?></td>
                      <td class="px-4 py-2 text-center"><?= htmlspecialchars($g['contact']) ?></td>
                      <td class="px-4 py-2 text-center"><?= htmlspecialchars($g['license_no']) ?></td>
                      <td class="px-4 py-2 text-center">
                        <?php if (!empty($g['schedule_id'])): ?>
                          Tour #<?= $g['schedule_id'] ?> - <?= $g['depart_date'] ?> (<?= $g['meeting_point'] ?>)
                        <?php else: ?>
                          Chưa phân công
                        <?php endif; ?>
                      </td>
                      <td class="px-4 py-2 text-center space-x-2">
                        <a href="<?= BASE_URL ?>?route=/guides/edit&id=<?= $g['guide_id'] ?>"
                          class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-black rounded text-xs">Sửa</a>
                        <a href="<?= BASE_URL ?>?route=/guides/delete&id=<?= $g['guide_id'] ?>"
                          onclick="return confirm('Xóa HDV này?')"
                          class="px-3 py-1 bg-red-600 hover:bg-red-700 text-black rounded text-xs">Xóa</a>
                        <a href="<?= BASE_URL ?>?route=/guides/assign&id=<?= $g['guide_id'] ?>"
                          class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-black rounded text-xs">Phân công</a>
                      </td>
                    </tr>
                  <?php endforeach;
                else: ?>
                  <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Không có hướng dẫn viên.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>