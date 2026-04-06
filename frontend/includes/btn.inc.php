<?php if(isset($btnLink)): ?>
    <a href="<?= $btnLink ?>" class="btn <?= $btnClass ?>">
        <?= $btnLabel ?>
    </a>
<?php else: ?>
    <button class="btn <?= $btnClass ?>">
        <?= $btnLabel ?>
    </button>
<?php endif; ?>