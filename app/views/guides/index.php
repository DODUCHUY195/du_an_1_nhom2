<h2>Guides</h2>
<p><a href="/guides/create">Thêm HDV</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>License</th><th>Note</th><th>Action</th></tr>
<?php foreach($guides as $g): ?>
<tr>
  <td><?=htmlspecialchars($g['guide_id'])?></td>
  <td><?=htmlspecialchars($g['user_name'])?></td>
  <td><?=htmlspecialchars($g['license_no'])?></td>
  <td><?=htmlspecialchars($g['note'])?></td>
  <td><a href="/guides/edit/<?=htmlspecialchars($g['guide_id'])?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</table>
