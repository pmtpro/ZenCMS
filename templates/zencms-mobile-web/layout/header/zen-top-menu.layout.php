<?php
$manager = ZenView::get_menu('manager');
$main = ZenView::get_menu('main');
$app = ZenView::get_menu('app');
?>
<div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <div class="navbar-brand zen-logo">
        <a href="<?php echo HOME ?>"><img src="<?php echo _URL_FILES_SYSTEMS ?>/images/logo-white.png" alt="Home" class="logo" width="200px"></a>
    </div>
</div> <!-- /.navbar-header -->
<div class="navbar-collapse collapse" style="height: 1px;">
    <ul class="nav navbar-nav">
        <li>
            <div class="visible-xs hd_guest">
                <ul class="u_table">
                    <?php if (!IS_MEMBER): ?>
                        <li>
                            <a href="<?php echo HOME ?>/login">
                                <img src="<?php echo _BASE_TEMPLATE ?>/theme/images/icons/ide.jpg" class="avatar_img" alt="Đăng nhập">
                            </a>
                        </li>
                        <li style="width:100%;">
                            <a href="<?php echo HOME ?>/register">
                                <div class="uname">Khách</div>
                                <div>
                                    <span class="link">Đăng kí</span>
                                </div>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo HOME ?>/account/wall/<?php echo $_client['username'] ?>">
                                <img src="<?php echo $_client['full_avatar'] ?>" class="avatar_img" alt="<?php echo $_client['nickname'] ?>">
                            </a>
                        </li>
                        <li style="width:100%;">
                            <a href="<?php echo HOME ?>/account">
                                <div class="uname"><?php echo $_client['nickname'] ?></div>
                                <div>
                                    <span class="link"><a href="<?php echo HOME ?>/account">Hồ sơ</a></span>
                                </div>
                            </a>
                        </li>
                    <?php endif ?>
                </ul> <!-- /u_table -->
            </div> <!-- /hd_guest -->
        </li>
        <?php if ($manager['menu']) foreach($manager['menu'] as $item): ?>
            <li><a href="<?php echo $item['full_url'] ?>"><i class="<?php echo $item['icon'] ?>"></i> <?php echo $item['name'] ?></a></li>
        <?php endforeach ?>
        <li><a href="<?php echo HOME ?>/search"><i class="glyphicon glyphicon-search"></i> Tìm kiếm</a></li>

        <!-- header_top_menu widget -->
        <?php widget_group('header_top_menu') ?>
        <!-- end header_top_menu widget -->

        <?php if ($main['menu']) foreach($main['menu'] as $item): ?>
            <li><a href="<?php echo $item['full_url'] ?>"><?php echo $item['name'] ?></a></li>
        <?php endforeach ?>
        <li></li>
        <?php if ($app['menu']) foreach($app['menu'] as $item): ?>
            <li><a href="<?php echo $item['full_url'] ?>"><?php echo $item['name'] ?></a></li>
        <?php endforeach ?>
    </ul>
</div><!-- /.nav-collapse -->