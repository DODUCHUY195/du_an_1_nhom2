
<?php require_once "./views/layouts/header.php"; ?>

<?php require_once  "./views/layouts/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

    <?php require_once  "./views/layouts/navbar.php"; ?>

    <div class="w-full px-6 py-6 mx-auto">
       <style>
  table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
  }
  caption {
    text-align: left;
    padding: 8px 0;
    font-weight: 600;
  }
  th, td {
    padding: 10px 12px;
    border: 1px solid #ddd;
  }
  thead th {
    background: #f4f4f4;
    text-align: left;
  }
  tbody tr:nth-child(odd) {
    background: #fafafa;
  }
  tbody tr:hover {
    background: #eef6ff;
  }
  th:first-child, td:first-child {
    width: 50px;
    text-align: center;
  }
</style>

<table>
  <caption>Danh sách người dùng</caption>
  <thead>
    <tr>
      <th>#</th>
      <th>Họ tên</th>
      <th>Email</th>
      <th>Vai trò</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>Nguyễn Văn A</td>
      <td>nguyenvana@example.com</td>
      <td>User</td>
    </tr>
    <tr>
      <td>2</td>
      <td>Trần Thị B</td>
      <td>tranthib@example.com</td>
      <td>Admin</td>
    </tr>
    <tr>
      <td>3</td>
      <td>Đỗ Đức Huy</td>
      <td>dohuy@example.com</td>
      <td>Moderator</td>
    </tr>
  </tbody>
</table>


    </div>

</main>

<?php require_once  "./views/layouts/footer.php"; ?>
