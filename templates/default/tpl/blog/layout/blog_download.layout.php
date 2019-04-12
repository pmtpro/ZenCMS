<div class="download">
    <div class="btn_download"><a name="download">Tải về</a></div>
    <?php foreach ($blog['downloads']['links'] as $link): ?>
        <div class="list">
            <a href="<?php echo $link['link'] ?>" rel="nofollow"><?php echo $link['name'] ?></a>
            <span class="text_smaller gray">(<?php echo $link['click'] ?> Click)</span>
        </div>
    <?php endforeach; ?>

    <?php foreach ($blog['downloads']['files'] as $file): ?>
        <div class="list">
            <a href="<?php echo $file['link'] ?>" rel="nofollow"><?php echo $file['name'] ?></a>
            <span class="text_smaller gray">(<?php echo get_size($file['size']) ?> | <?php echo $file['down'] ?> lượt tải)</span>
        </div>
    <?php endforeach; ?>

</div>