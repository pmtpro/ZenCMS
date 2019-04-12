<div class="container">
    <ul>
        <?php if (is(ROLE_MANAGER)): ?>
            <li><a href="<?php echo HOME ?>/admin" rel="nofollow">ADMIN CP</a></li>
        <?php endif ?>
        <li><a href="http://zencms.vn" target="_blank">Trang chủ</a></li>
        <li><a href="http://zencms.vn/license" target="_blank">Điều khoản sử dụng</a></li>
        <?php echo phook('bottom_menu', '', array('callback' => function($item) { return '<li>' . $item . '</li>';})) ?>
        <?php widget_group('bottom_menu') ?>
    </ul>
</div>