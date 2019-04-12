<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-phone"></i><span>+84.123456789</span></li>
                    <li><i class="fa fa-envelope-o"></i><span>example@zencms.vn</span></li>
                    <?php if (is(ROLE_MANAGER)): ?>
                        <li><i class="fa fa-user"></i><a href="<?php echo HOME ?>/admin"><span>Admin CP</span></a></li>
                    <?php endif ?>
                </ul>
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-6 col-sm-6 additional-nav">
                <ul class="list-unstyled list-inline pull-right">
                    <?php if (IS_MEMBER) : ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="<?php echo HOME ?>/account">
                                Xin chào <i class="fa fa-user"></i> <?php echo $_client['nickname'] ?> <?php echo hook('account', 'user_detail_box_after_name')?>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo HOME ?>/account">Chỉnh sửa tài khoản</a></li>
                                <li><a href="<?php echo HOME ?>/account/settings">Cài đặt tài khoản</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo HOME ?>/logout">Thoát</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo HOME ?>/login">Đăng nhập</a></li>
                        <li><a href="<?php echo HOME ?>/register">Đăng kí</a></li>
                    <?php endif ?>
                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>
</div>
<!-- END TOP BAR -->