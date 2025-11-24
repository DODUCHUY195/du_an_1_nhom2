
<?php require_once "./views/layouts/header.php"; ?>

<?php require_once  "./views/layouts/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

    <?php require_once  "./views/layouts/navbar.php"; ?>

    <div class="w-full px-6 py-6 mx-auto">
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
