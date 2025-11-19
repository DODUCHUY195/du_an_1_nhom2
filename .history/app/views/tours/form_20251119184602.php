<?php require_once "./views/layouts/admin/header.php"; ?>

<?php require_once  "./views/layouts/admin/sidebar.php"; ?>
<h2><?= isset($tour) ? 'Chỉnh sửa tour' : 'Tạo tour mới' ?></h2>
<form method="post">
  <label>Code <input type="text" name="tour_code" value="<?=isset($tour['tour_code'])?htmlspecialchars($tour['tour_code']):''?>" required></label><br>
  <label>Name <input type="text" name="tour_name" value="<?=isset($tour['tour_name'])?htmlspecialchars($tour['tour_name']):''?>" required></label><br>
  <label>Category
    <select name="category_id">
      <option value="">--Chọn--</option>
      <?php foreach($categories as $c): ?>
        <option value="<?=htmlspecialchars($c['category_id'])?>" <?=isset($tour) && $tour['category_id']==$c['category_id']?'selected':''?>><?=htmlspecialchars($c['category_name'])?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>Price <input type="number" name="price" value="<?=isset($tour['price'])?htmlspecialchars($tour['price']):0?>" step="0.01"></label><br>
  <label>Duration days <input type="number" name="duration_days" value="<?=isset($tour['duration_days'])?htmlspecialchars($tour['duration_days']):1?>"></label><br>
  <label>Description<br><textarea name="description"><?=isset($tour['description'])?htmlspecialchars($tour['description']):''?></textarea></label><br>
  <label>Status
    <select name="status">
      <option value="draft" <?=isset($tour) && $tour['status']=='draft'?'selected':''?>>Draft</option>
      <option value="published" <?=isset($tour) && $tour['status']=='published'?'selected':''?>>Published</option>
    </select>
  </label><br>
  <button><?= isset($tour) ? 'Update' : 'Create' ?></button>
</form>
