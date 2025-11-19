
<?php require_once "./views/layouts/header.php"; ?>

<?php require_once  "./views/layouts/sidebar.php"; ?>

<main class="">

    <?php require_once  "./views/layouts/navbar.php"; ?>

    <div class="w-full px-6 py-6 mx-auto">
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
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">STT</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Mô tả</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Danh Mục</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Thao tác</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                    
                     
                        
                    
                      <tr>
                       <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">abc</td>
                         <!-- <?php foreach ($listDanhMuc as $key => $danhMuc): ?>
                    <tr>
                      <td><?= $key + 1 ?></td>
                      <td><?= $danhMuc['ten_danh_muc'] ?></td>
                      <td><?= $danhMuc['mo_ta'] ?></td>
                      <td>
                        <a class="btn btn-warning" href="<?= BASE_URL_ADMIN . '?act=formsuadanhmuc&id_danh_muc=' . $danhMuc['id'] ?>">
                          <i class="fas fa-wrench"></i>
                        </a>
                        <a class="btn btn-danger" href="<?= BASE_URL_ADMIN . '?act=xoadanhmuc&id_danh_muc=' . $danhMuc['id'] ?>" onclick="return confirm('Bạn có đồng ý xoá không')"><i class="fas fa-trash-alt"></i></a>

                      </td>
                    </tr>
                  <?php endforeach ?> -->

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

<?php require_once  "./views/layouts/footer.php"; ?>
