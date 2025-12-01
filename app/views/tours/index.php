<?php require_once "./views/layouts/admin/header.php"; ?>
<?php require_once  "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <?php require_once  "./views/layouts/admin/navbar.php"; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Danh sách Tour</h2>
      <a href="<?= BASE_URL . '?route=/tours/addForm' ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Thêm Tour
      </a>
    </div>

    <!-- Advanced Search and Filter Form -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
      <form method="GET" action="<?= BASE_URL ?>" class="space-y-4">
        <input type="hidden" name="route" value="/tours">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search by name or code -->
          <div>
            <label for="search" class="block mb-2 font-medium text-gray-700">Tìm kiếm</label>
            <input
              type="text"
              id="search"
              name="search"
              value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
              placeholder="Tên tour hoặc mã tour"
              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent" />
          </div>



          <!-- Price range filter -->
          <div>
            <label for="price_range" class="block mb-2 font-medium text-gray-700">Khoảng giá</label>
            <select id="price_range" name="price_range" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent">
              <option value="">Tất cả giá</option>
              <option value="0-1000000" <?= (isset($_GET['price_range']) && $_GET['price_range'] == '0-1000000') ? 'selected' : '' ?>>0 - 1.000.000 VNĐ</option>
              <option value="1000000-5000000" <?= (isset($_GET['price_range']) && $_GET['price_range'] == '1000000-5000000') ? 'selected' : '' ?>>1.000.000 - 5.000.000 VNĐ</option>
              <option value="5000000-10000000" <?= (isset($_GET['price_range']) && $_GET['price_range'] == '5000000-10000000') ? 'selected' : '' ?>>5.000.000 - 10.000.000 VNĐ</option>
              <option value="10000000-100000000" <?= (isset($_GET['price_range']) && $_GET['price_range'] == '10000000-100000000') ? 'selected' : '' ?>>Trên 10.000.000 VNĐ</option>
            </select>
          </div>

          <!-- Status filter -->
          <div>
            <label for="status" class="block mb-2 font-medium text-gray-700">Trạng thái</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent">
              <option value="">Tất cả trạng thái</option>
              <?php
              $tourModel = new Tour();
              $statusOptions = $tourModel->getStatusOptions();
              foreach ($statusOptions as $statusValue => $statusLabel): ?>
                <option value="<?= $statusValue ?>" <?= (isset($_GET['status']) && $_GET['status'] == $statusValue) ? 'selected' : '' ?>>
                  <?= $statusLabel ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 pt-4">
          <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-150">
            <i class="fas fa-search mr-2"></i> Tìm kiếm
          </button>
          <a href="<?= BASE_URL . '?route=/tours' ?>" class="bg-gray-500 hover:bg-gray-600 text-black font-medium py-2 px-4 rounded transition duration-150" title="Xóa tất cả bộ lọc">
            <i class="fas fa-times mr-2"></i> Xóa bộ lọc
          </a>
        </div>
      </form>
    </div>

    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="text-xl font-bold">Danh Sách Tours</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
              <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                <thead class="align-bottom">
                  <tr class="bg-gray-100">
                    <th class="px-6 py-3 font-bold text-left uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      STT
                    </th>
                    <th class="px-6 py-3 font-bold text-left uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Hình ảnh
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Mã tour
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Tên tour
                    </th>

                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Giá
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Số ngày
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Ngày tạo
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Trạng thái
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle text-xs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Thao tác
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($listTour)): ?>
                    <?php foreach ($listTour as $key => $tour): ?>
                      <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm"><?= $key + 1 + ($page - 1) * 5 ?></td>
                        <td class="px-6 py-3">
                         <?php if (!empty($tour['image'])): ?>
                                    <img src="<?= BASE_URL ?>./<?= $tour['image'] ?>" alt="Tour Image" class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                <?php endif; ?>

                        </td>
                        <td class="px-6 py-3 text-sm font-medium"><?= $tour['tour_code'] ?></td>
                        <td class="px-6 py-3 text-sm"><?= $tour['tour_name'] ?></td>

                        <td class="px-6 py-3 text-sm text-center font-medium"><?= number_format($tour['price'], 0, ',', '.') ?> VNĐ</td>
                        <td class="px-6 py-3 text-sm text-center"><?= $tour['duration_days'] ? $tour['duration_days'] . ' ngày' : 'Chưa xác định' ?></td>
                        <td class="px-6 py-3 text-sm text-center"><?= date('d/m/Y', strtotime($tour['created_at'])) ?></td>
                        <td class="px-6 py-3 text-center">
                          <?php
                          $tourModel = new Tour();
                          $statusBadgeClass = $tourModel->getStatusBadgeClass($tour['status']);
                          $statusLabel = $tourModel->getStatusOptions()[$tour['status']] ?? 'Không xác định';
                          ?>
                          <span class="px-2 py-1 rounded text-xs font-medium <?= $statusBadgeClass ?>">
                            <?= $statusLabel ?>
                          </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                          <a class="btn btn-info text-xs mr-2 px-3 py-1 rounded bg-blue-100 text-blue-800 hover:bg-blue-200" href="<?= BASE_URL . '?route=/tours/detail&tour_id=' . $tour['tour_id'] ?>">
                            Xem
                          </a>
                          <a class="btn btn-primary text-xs mr-2 px-3 py-1 rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200" href="<?= BASE_URL . '?route=/tours/editForm&tour_id=' . $tour['tour_id'] ?>">
                            Sửa
                          </a>
                          <a class="btn btn-danger text-xs px-3 py-1 rounded bg-red-100 text-red-800 hover:bg-red-200" href="<?= BASE_URL . '?route=/tours/delete&tour_id=' . $tour['tour_id'] ?>"
                            onclick="return confirm('Bạn có đồng ý xoá/ẩn tour không?')">Xóa</a>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                          <i class="fas fa-search text-4xl text-gray-300 mb-2"></i>
                          <p class="text-lg">Không tìm thấy tour nào phù hợp với điều kiện tìm kiếm.</p>
                          <a href="<?= BASE_URL . '?route=/tours' ?>" class="mt-4 text-blue-500 hover:text-blue-700 font-medium">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách đầy đủ
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <?php if (!empty($listTour) && $totalPages > 1): ?>
          <div class="flex flex-col md:flex-row justify-between items-center px-4 py-3 bg-white rounded-lg shadow mt-4">
            <div class="text-sm text-gray-700 mb-4 md:mb-0">
              Hiển thị <span class="font-medium"><?= count($listTour) ?></span> tour trên tổng số <span class="font-medium"><?= $totalTours ?></span> tour
            </div>

            <div class="flex space-x-1">
              <?php
              // Build query string for pagination links
              $queryString = '';
              if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
                $queryString .= '&category_id=' . $_GET['category_id'];
              }
              if (isset($_GET['search']) && $_GET['search'] != '') {
                $queryString .= '&search=' . urlencode($_GET['search']);
              }
              if (isset($_GET['price_range']) && $_GET['price_range'] != '') {
                $queryString .= '&price_range=' . $_GET['price_range'];
              }
              if (isset($_GET['status']) && $_GET['status'] != '') {
                $queryString .= '&status=' . $_GET['status'];
              }
              ?>

              <?php if ($page > 1): ?>
                <a href="<?= BASE_URL . '?route=/tours&page=' . ($page - 1) . $queryString ?>"
                  class="px-3 py-2 rounded-l border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-chevron-left"></i>
                </a>
              <?php endif; ?>

              <?php
              // Calculate start and end page numbers to display
              $startPage = max(1, $page - 2);
              $endPage = min($totalPages, $page + 2);

              // Show first page and ellipsis if needed
              if ($startPage > 1) {
              ?>
                <a href="<?= BASE_URL . '?route=/tours&page=1' . $queryString ?>"
                  class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                  1
                </a>
                <?php
                if ($startPage > 2) {
                  echo '<span class="px-3 py-2 border border-gray-300 bg-white text-gray-700">...</span>';
                }
              }

              // Show page numbers
              for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="<?= BASE_URL . '?route=/tours&page=' . $i . $queryString ?>"
                  class="px-3 py-2 border border-gray-300 <?= $i == $page ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                  <?= $i ?>
                </a>
              <?php endfor; ?>

              <?php
              // Show last page and ellipsis if needed
              if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                  echo '<span class="px-3 py-2 border border-gray-300 bg-white text-gray-700">...</span>';
                }
              ?>
                <a href="<?= BASE_URL . '?route=/tours&page=' . $totalPages . $queryString ?>"
                  class="px-3 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                  <?= $totalPages ?>
                </a>
              <?php
              }
              ?>

              <?php if ($page < $totalPages): ?>
                <a href="<?= BASE_URL . '?route=/tours&page=' . ($page + 1) . $queryString ?>"
                  class="px-3 py-2 rounded-r border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-chevron-right"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</main>

<?php require_once  "./views/layouts/admin/footer.php"; ?>