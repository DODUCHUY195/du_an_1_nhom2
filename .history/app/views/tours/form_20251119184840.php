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
              <form method="post" class="p-4">

  <div class="mb-3">
    <label class="form-label fw-bold">Code</label>
    <input type="text" name="tour_code"
           class="form-control"
           value="<?= isset($tour['tour_code']) ? htmlspecialchars($tour['tour_code']) : '' ?>"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Name</label>
    <input type="text" name="tour_name"
           class="form-control"
           value="<?= isset($tour['tour_name']) ? htmlspecialchars($tour['tour_name']) : '' ?>"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Category</label>
    <select name="category_id" class="form-select">
      <option value="">--Chọn--</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= htmlspecialchars($c['category_id']) ?>"
          <?= isset($tour) && $tour['category_id'] == $c['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['category_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Price</label>
    <input type="number" step="0.01" name="price"
           class="form-control"
           value="<?= isset($tour['price']) ? htmlspecialchars($tour['price']) : 0 ?>">
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Duration (days)</label>
    <input type="number" name="duration_days"
           class="form-control"
           value="<?= isset($tour['duration_days']) ? htmlspecialchars($tour['duration_days']) : 1 ?>">
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Description</label>
    <textarea name="description" rows="4" class="form-control"><?= isset($tour['description']) ? htmlspecialchars($tour['description']) : '' ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Status</label>
    <select name="status" class="form-select">
      <option value="draft" <?= isset($tour) && $tour['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="published" <?= isset($tour) && $tour['status'] == 'published' ? 'selected' : '' ?>>Published</option>
    </select>
  </div>

  <button class="btn btn-primary"><?= isset($tour) ? 'Update' : 'Create' ?></button>

</form>


            </div>
          </div>
        </div>
      </div>
    </div>


  </div>

</main>


<?php require_once  "./views/layouts/admin/footer.php"; ?>