<h2>Schedules</h2>
<p><a href="/schedules/create">Tạo lịch khởi hành</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Tour</th><th>Depart date</th><th>Seats total</th><th>Seats booked</th><th>Status</th><th>Action</th></tr>
<?php foreach($schedules as $s): ?>
<tr>
  <td><?=htmlspecialchars($s['schedule_id'])?></td>
  <td><?=htmlspecialchars($s['tour_name'])?></td>
  <td><?=htmlspecialchars($s['depart_date'])?></td>
  <td><?=htmlspecialchars($s['seats_total'])?></td>
  <td><?=htmlspecialchars($s['seats_booked'])?></td>
  <td><?=htmlspecialchars($s['status'])?></td>
  <td><a href="/schedules/edit/<?=htmlspecialchars($s['schedule_id'])?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</table>
