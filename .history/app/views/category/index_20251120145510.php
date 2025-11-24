<?php require_once "./views/layouts/admin/header.php"; ?>

<?php require_once  "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">

  <?php require_once  "./views/layouts/admin/navbar.php"; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <h2><a href="<?= BASE_URL . '?route=/categories/addForm' ?>">Them danh muc</a></h2>
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Danh Mục</h6>
          </div>
          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
              <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                  <tr>
                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      STT
                    </th>
                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Mô tả
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Danh Mục
                    </th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                      Thao tác
                    </th>

                  </tr>
                </thead>
                <tbody>


                  <tr>

                    <?php foreach ($listDanhMuc as $key => $danhMuc): ?>
                  <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $danhMuc['category_name'] ?></td>
                    
                    <td><?= $danhMuc['description'] ?></td>
                    <td>
                      <a class="btn btn-primary" href="<?= BASE_URL . '?route=/categories/editForm&category_id='. $danhMuc['category_id '] ?>">
                        Sua
                      </a>
                      <a class="btn btn-danger" href="" onclick="return confirm('Bạn có đồng ý xoá không')"><i class="fas fa-trash-alt"></i>Xoa</a>

                    </td>
                  </tr>
                <?php endforeach ?>

                </tr>


                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>

</main>

<?php require_once  "./views/layouts/admin/footer.php"; ?>