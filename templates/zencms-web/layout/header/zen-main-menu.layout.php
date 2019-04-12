<ul class="top-menu">
    <li class="link">
        <a href="<?php echo HOME ?>" title="<?php echo dbConfig('title')?>" class="link_title">
            <i class="glyphicon glyphicon-home"></i>
        </a>
    </li>
    <li class="link">
        <a href="<?php echo HOME ?>/search" title="Tìm kiếm" class="link_title">
            <i class="glyphicon glyphicon-search"></i> Tìm kiếm
        </a>
    </li>
    <?php widget_group('header_main_menu') ?>
    <?php if (!IS_MEMBER): ?>
        <li class="link">
            <a href="<?php echo HOME ?>/login" title="Đăng nhập thành viên" class="link_title"><i class="glyphicon glyphicon-log-in"></i> Đăng nhập</a>
        </li>
        <li class="link">
            <a href="<?php echo HOME ?>/register" title="Đăng kí thành viên" class="link_title"><i class="glyphicon glyphicon-user"></i> Đăng kí</a>
        </li>
    <?php else: ?>
        <li class="link">
            <a href="<?php echo HOME ?>/account" title="Tài khoản" class="link_title"><i class="glyphicon glyphicon-user"></i> Tài khoản</a>
        </li>
        <li class="link">
            <a href="<?php echo HOME ?>/logout" title="Đăng xuất" class="link_title"><i class="glyphicon glyphicon-log-out"></i> Đăng xuất</a>
        </li>
    <?php endif ?>
    <li style="text-align:right; width:100%;"> </li>
</ul>