<h2><?= isset($guide) ? 'Chỉnh sửa HDV' : 'Thêm HDV' ?></h2>
<form method="post">
  <label>User (assign) <select name="user_id"><option value="">--Chọn user--</option><?php foreach($users as $u): ?><option value="<?=htmlspecialchars($u['user_id'])?>" <?=isset($guide) && $guide['user_id']==$u['user_id']?'selected':''?>><?=htmlspecialchars($u['full_name'])?></option><?php endforeach; ?></select></label><br>
  <label>License no <input type="text" name="license_no" value="<?=isset($guide['license_no'])?htmlspecialchars($guide['license_no']):''?>"></label><br>
  <label>Note<br><textarea name="note"><?=isset($guide['note'])?htmlspecialchars($guide['note']):''?></textarea></label><br>
  <button><?= isset($guide) ? 'Update' : 'Create' ?></button>
</form>
