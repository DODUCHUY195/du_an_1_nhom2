<?php require_once __DIR__ . '/../layouts/admin/header.php'; ?>

<?php require_once  __DIR__ . '/../layouts/admin/sidebar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

  <?php require_once  __DIR__ . '/../layouts/admin/navbar.php'; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <h2><a href="<?= BASE_URL . '?route=/categories/addForm' ?>">Thêm danh mục</a></h2>
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl border-b-transparent">
            <h6>Danh Mục</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
              <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                <thead class="align-bottom">
                  <tr>
                    <th>STT</th>
                    <th>Mô tả</th>
                    <th>Danh Mục</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $rows = $categories ?? ($listDanhMuc ?? []);
                  if (!empty($rows)): ?>
                    <?php foreach ($rows as $key => $c): ?>
                      <tr>
                        <td><?= htmlspecialchars($c['category_id'] ?? ($key + 1)) ?></td>
                        <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['category_name'] ?? '') ?></td>
                        <td>
                          <a class="btn btn-primary" href="<?= BASE_URL . '?route=/categories/editForm&category_id=' . urlencode($c['category_id'] ?? ($key + 1)) ?>">
                            Sửa
                          </a>

                          <form method="post" action="<?= BASE_URL . '?route=/categories/delete' ?>" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($c['category_id'] ?? ($key + 1)) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="4" style="text-align:center;">Không có dữ liệu</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<?php require_once  __DIR__ . '/../layouts/admin/footer.php'; ?>
