 <!-- navbar md-->
 <div class="d-none d-md-block fixed-top shadow shadow-sm">

     <div class="fixed-top bg-dark border-bottom border-secondary py-3">
         <div class="container">
             <div class="d-flex justify-content-between">
                 <div class="d-flex gap-2">
                     <?php foreach (menus("grup") as $i): ?>
                         <?php if ($i['grup'] == ""): ?>
                             <a href="<?= base_url($i['controller']); ?>" class="btn btn-sm <?= (url() == $i['controller'] ? "btn-light" : "btn-secondary"); ?>"><i class="<?= $i['icon']; ?>"></i> <?= $i['menu']; ?></a>
                         <?php else: ?>
                             <div class="nav-item dropdown btn btn-sm <?= (menu(url())['grup'] == $i['grup'] ? 'btn-light' : 'btn-secondary'); ?>">
                                 <a class="nav-link dropdown-toggle <?= (menu(url())['grup'] == $i['grup'] ? 'btn-light' : 'btn-secondary'); ?>" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><i class="<?= $i['icon']; ?>"></i> <?= $i['grup']; ?></a>
                                 <ul class="dropdown-menu bg-dark border border-secondary p-1">
                                     <?php foreach (menus($i['grup']) as $k => $m): ?>
                                         <li class="d-grid <?= ($k > 0 ? "mt-1" : ""); ?>"><a style="text-align: left;font-size:small" href="<?= base_url($m['controller']); ?>" class="btn btn-sm <?= (url() == $m['controller'] ? "btn-light" : "btn-secondary"); ?>"><i class="<?= $m['icon']; ?>"></i> <?= $m['menu']; ?></a></li>
                                     <?php endforeach; ?>
                                 </ul>
                             </div>
                         <?php endif; ?>
                     <?php endforeach; ?>

                 </div>

                 <div class="d-flex justify-content-end gap-2">
                     <?php if (user()): ?>
                         <div class="btn btn-sm btn-dark border border-secondary"><?= user()['nama']; ?></div>
                         <a href="<?= base_url('logout'); ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                     <?php else: ?>
                         <div class="btn btn-sm btn-dark"><?= date('M, d Y'); ?></div>
                         <div class="btn btn-sm btn-dark"><?= date('H:i'); ?></div>

                     <?php endif; ?>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- navbar sm -->
 <div class="d-block d-md-none d-sm-block fixed-top" style="font-size: 12px;">

     <div class="fixed-top bg-dark border-bottom border-secondary py-3">
         <div class="container">
             <div class="d-flex justify-content-between">
                 <a class="text-light" href=""><i class="<?= menu()['icon']; ?>"></i> <?= menu()['menu']; ?></a>
                 <a data-bs-toggle="offcanvas" data-bs-target="#leftMenu" aria-controls="leftMenu" class="text-light" href=""><i class="fa-solid fa-bars"></i></a>
             </div>
         </div>
     </div>
     <!-- camvas -->
     <div class="offcanvas offcanvas-start" style="width:90%" data-bs-scroll="true" tabindex="-1" id="leftMenu" aria-labelledby="leftMenuLabel">

         <div class="offcanvas-body bg-dark">
             <div class="d-flex justify-content-between border-bottom border-light pb-2">
                 <div>MENU</div>
                 <div><a href="" role="button" class="text-light" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-circle-xmark"></i></a></div>
             </div>
             <?php foreach (menus() as $i): ?>
                 <div class="d-grid mt-2 <?= url() == $i['controller'] ? "bg-secondary" : "bg-secondary bg-opacity-25"; ?> py-1 px-2 rounded"><a href="<?= base_url($i['controller']); ?>" role="button" class="text-light"><i class="<?= $i['icon']; ?>"></i> <?= $i['menu']; ?></a></div>
             <?php endforeach; ?>
         </div>
     </div>


 </div>