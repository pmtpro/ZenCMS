<?php $application = ZenView::get_menu('application')?>
<ul class="nav navbar-collapse collapse navbar-collapse-primary">
    <li>
        <span class="glow"></span>
        <a href="<?php echo HOME ?>/admin">
            <i class="icon-dashboard icon-2x"></i>
            <span>Dashboard</span>
        </a>
    </li>
<?php if(!empty($application['menu'])) foreach ($application['menu'] as $menu): ?>
    <li<?php echo (!empty($menu['sub_menus'])? ' class="dark-nav"' : '') ?>>
        <span class="glow"></span>
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#<?php echo $menu['id'] ?>">
            <i class="<?php echo $menu['icon'] ?> icon-2x"></i>
            <span><?php echo $menu['name'] ?></span>
            <?php if(!empty($menu['sub_menus'])):  ?>
                <i class="icon-caret-down"></i>
            <?php endif ?>
        </a>
        <?php if(!empty($menu['sub_menus'])):  ?>
            <ul class="collapse" id="<?php echo $menu['id'] ?>">
                <?php foreach ($menu['sub_menus'] as $sub): ?>
                    <li<?php echo (is_child_page($sub['full_url']) ? ' class="active"':'') ?>>
                        <?php if (is_child_page($sub['full_url'])): ?>
                            <script>
                                $("ul#<?php echo $menu['id'] ?>").removeClass("collapse").addClass("collapse in");
                            </script>
                        <?php endif ?>
                        <a href="<?php echo $sub['full_url'] ?>" title="<?php echo $sub['title'] ?>">
                            <i class="<?php echo $sub['icon'] ?>"></i> <?php echo $sub['name'] ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </li>
<?php endforeach ?>
</ul>
<div class="sparkline-box side copyright">
    <hr class="divider"/>
    <div class="sparkline-row">
        Power by <a href="http://zencms.vn" target="_blank" title="ZenCMS - Web developers">ZenCMS</a>
    </div>
    <hr class="divider"/>
    <div class="sparkline-row">
        &copy; 2012-2014 <a href="http://zenthang.com" target="_blank" title="Zen Thắng">Zen Thắng</a>
    </div>
    <hr class="divider"/>
</div>
