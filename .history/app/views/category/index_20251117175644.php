<h2>Categories</h2>
<p><a href="/categories/create">Tạo danh mục</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Description</th></tr>
<?php foreach($categories as $c): ?>
<tr>
  <td><?=htmlspecialchars($c['category_id'])?></td>
  <td><?=htmlspecialchars($c['category_name'])?></td>
  <td><?=htmlspecialchars($c['description'])?></td>
</tr>
<?php endforeach; ?>
</table>
