<?php require_once "./views/layouts/admin/header.php"; ?>

<?php require_once  "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

  <?php require_once  "./views/layouts/admin/navbar.php"; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <h2><a href="<?= BASE_URL . '?route=/tours/create' ?>">Them danh muc</a></h2>
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Danh Mục</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
              <form method="post" class="container py-4">

  <div class="row g-4">

    <!-- LEFT COLUMN -->
    <div class="col-md-6">

      <div class="mb-3">
        <label class="form-label fw-semibold">Code</label>
        <input type="text" name="tour_code"
          class="form-control form-control-lg"
          value="<?= isset($tour['tour_code']) ? htmlspecialchars($tour['tour_code']) : '' ?>"
          required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Name</label>
        <input type="text" name="tour_name"
          class="form-control form-control-lg"
          value="<?= isset($tour['tour_name']) ? htmlspecialchars($tour['tour_name']) : '' ?>"
          required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Category</label>
        <select name="category_id" class="form-select form-select-lg">
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c['category_id']) ?>"
              <?= isset($tour) && $tour['category_id'] == $c['category_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Price</label>
        <input type="number" step="0.01" name="price"
          class="form-control form-control-lg"
          value="<?= isset($tour['price']) ? htmlspecialchars($tour['price']) : 0 ?>">
      </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-md-6">

      <div class="mb-3">
        <label class="form-label fw-semibold">Duration (days)</label>
        <input type="number" name="duration_days"
          class="form-control form-control-lg"
          value="<?= isset($tour['duration_days']) ? htmlspecialchars($tour['duration_days']) : 1 ?>">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select form-select-lg">
          <option value="draft" <?= isset($tour) && $tour['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="published" <?= isset($tour) && $tour['status'] == 'published' ? 'selected' : '' ?>>Published</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="6"
          class="form-control form-control-lg"><?= isset($tour['description']) ? htmlspecialchars($tour['description']) : '' ?></textarea>
      </div>

    </div>
  </div>

  <div class="mt-4">
    <button class="btn btn-primary btn-lg px-4">
      <?= isset($tour) ? 'Update' : 'Create' ?>
    </button>
  </div>

</form>


            </div>
          </div>
        </div>
      </div>
    </div>


  </div>

</main>


<?php require_once  "./views/layouts/admin/footer.php"; ?>