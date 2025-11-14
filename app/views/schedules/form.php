<h2><?= isset($schedule) ? 'Chỉnh sửa lịch' : 'Tạo lịch mới' ?></h2>
<form method="post">
  <label>Tour
    <select name="tour_id">
      <?php foreach($tours as $t): ?>
        <option value="<?=htmlspecialchars($t['tour_id'])?>" <?=isset($schedule) && $schedule['tour_id']==$t['tour_id']?'selected':''?>><?=htmlspecialchars($t['tour_name'])?></option>
      <?php endforeach; ?>
    </select>
  </label><br>
  <label>Depart date <input type="date" name="depart_date" value="<?=isset($schedule['depart_date'])?htmlspecialchars($schedule['depart_date']):''?>"></label><br>
  <label>Meeting point <input type="text" name="meeting_point" value="<?=isset($schedule['meeting_point'])?htmlspecialchars($schedule['meeting_point']):''?>"></label><br>
  <label>Seats total <input type="number" name="seats_total" value="<?=isset($schedule['seats_total'])?htmlspecialchars($schedule['seats_total']):0?>"></label><br>
  <label>Status <select name="status"><option value="open">Open</option><option value="full">Full</option><option value="cancelled">Cancelled</option></select></label><br>
  <button><?= isset($schedule) ? 'Update' : 'Create' ?></button>
</form>
