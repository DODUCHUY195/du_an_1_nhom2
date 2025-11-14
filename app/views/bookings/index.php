<h2>Manage Bookings (Admin)</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Code</th><th>Tour</th><th>Depart</th><th>Customer</th><th>Total</th><th>Status</th><th>Action</th></tr>
<?php foreach($bookings as $b): ?>
<tr>
  <td><?=htmlspecialchars($b['booking_id'])?></td>
  <td><?=htmlspecialchars($b['booking_code'])?></td>
  <td><?=htmlspecialchars($b['tour_name'])?></td>
  <td><?=htmlspecialchars($b['depart_date'])?></td>
  <td><?=htmlspecialchars($b['full_name'])?></td>
  <td><?=htmlspecialchars($b['total_amount'])?></td>
  <td><?=htmlspecialchars($b['status'])?></td>
  <td>
    <form method="post" action="/admin/bookings/update">
      <input type="hidden" name="booking_id" value="<?=htmlspecialchars($b['booking_id'])?>">
      <select name="status">
        <option value="pending" <?= $b['status']=='pending'?'selected':'' ?>>Pending</option>
        <option value="confirmed" <?= $b['status']=='confirmed'?'selected':'' ?>>Confirmed</option>
        <option value="cancelled" <?= $b['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
        <option value="refunded" <?= $b['status']=='refunded'?'selected':'' ?>>Refunded</option>
      </select>
      <button>Update</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
