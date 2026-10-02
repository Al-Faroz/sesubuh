<?php if ($pager->getPageCount() > 1) :
    $cur  = $pager->getCurrentPage();
    $last = $pager->getPageCount(); ?>
    <nav class="pager" aria-label="Halaman data">
        <?php if ($cur > 1) : ?><a class="btn btn-sm" href="<?= current_url() . '?page=' . ($cur - 1) ?>"><i class="fas fa-chevron-left"></i> Sebelumnya</a><?php else : ?><span class="btn btn-sm is-off" aria-disabled="true"><i class="fas fa-chevron-left"></i> Sebelumnya</span><?php endif; ?>
        <span class="hint">Halaman <?= $cur ?> dari <?= $last ?></span>
        <?php if ($cur < $last) : ?><a class="btn btn-sm" href="<?= current_url() . '?page=' . ($cur + 1) ?>">Berikutnya <i class="fas fa-chevron-right"></i></a><?php else : ?><span class="btn btn-sm is-off" aria-disabled="true">Berikutnya <i class="fas fa-chevron-right"></i></span><?php endif; ?>
    </nav>
<?php endif; ?>
