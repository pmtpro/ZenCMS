<?php
$manager = ZenView::get_menu('manager');
$main = ZenView::get_menu('main');
$app = ZenView::get_menu('app');
?>

<div class="logo">
        <a href="<?php echo HOME ?>"><img src="<?php echo _BASE_TEMPLATE ?>/images/logo.png" alt="Home" class="logo"></a>
</div>
<div class="nav">
	<?php if(!IS_MEMBER): ?>
		<a href="<?php echo HOME ?>/login">Đăng nhập</a> |
		<a href="<?php echo HOME ?>/register">Đăng ký</a> |
	<?php else: ?>
		Hi <a href="<?php echo HOME ?>/account/wall/<?php echo $_client['username'] ?>"  style="color:#e65757; font-weight:bold;"><?php echo $_client['nickname']; ?></a> | 
		   <a href="<?php echo HOME ?>/logout">Thoát</a> | 
		<?php if (is(ROLE_MANAGER)): ?>
                <a href="<?php echo HOME ?>/admin" rel="nofollow">Admin CP</a> |
        <?php endif ?>
	<?php endif; ?>
    <a href="<?php echo HOME ?>/search">Tìm kiếm</a>
    <?php widget_group('header_main_menu') ?>
</div>