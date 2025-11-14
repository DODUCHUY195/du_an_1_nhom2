<h2>Danh sách tour</h2>
<p><a href="/tours/create">Tạo tour mới</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Code</th><th>Tên</th><th>Category</th><th>Price</th><th>Ngày tạo</th><th>Action</th></tr>
<?php foreach($tours as $t): ?>
<tr>
  <td><?=htmlspecialchars($t['tour_id'])?></td>
  <td><?=htmlspecialchars($t['tour_code'])?></td>
  <td><?=htmlspecialchars($t['tour_name'])?></td>
  <td><?=htmlspecialchars($t['category_name'])?></td>
  <td><?=htmlspecialchars($t['price'])?></td>
  <td><?=htmlspecialchars($t['created_at'])?></td>
  <td><a href="/tours/edit/<?=htmlspecialchars($t['tour_id'])?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</table>
