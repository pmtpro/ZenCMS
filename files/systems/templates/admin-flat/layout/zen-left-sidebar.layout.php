<?php $application = ZenView::get_menu('application')?>
<?php
$content = '';
$has_active = false;
$has_one_active = false;
?>
<?php if(!empty($application['menu'])) foreach ($application['menu'] as $menu): ?>
    <?php $subContent = ''; $has_active = false; ?>
    <?php if(!empty($menu['sub_menus'])): ?>
        <?php $subContent = '<ul class="sub-menu">'; ?>
        <?php foreach ($menu['sub_menus'] as $sub) {
            $active = false;
            if (is_child_page($sub['full_url'])) {$active = true; $has_active = true; $has_one_active = true;}
            $subContent .= '<li' . ($active?' class="active"':'') . '>
                    <a href="' . $sub['full_url'] . '" title="' . $sub['title'] . '">
                        <i class="' . $sub['icon'] . '"></i> ' . $sub['name'] . '
                    </a>
                </li>';
        }
        $subContent .= '</ul>'; ?>
    <?php endif ?>
    <?php $content .= '<li' . ($has_active ? ' class="active"':'') . '>
            <a href="javascript:;">
                <i class="' . $menu['icon'] . '"></i>
                <span class="title">' . $menu['name'] . '</span>
                ' . ($has_active ? '<span class="selected"></span>':'') . '
                ' . (!empty($menu['sub_menus']) ? '<span class="arrow"></span>' : '') . '
            </a>' . $subContent . '
        </li>'; ?>
<?php endforeach ?>
<ul class="page-sidebar-menu page-sidebar-menu-closed" data-auto-scroll="true" data-slide-speed="200">
    <li class="sidebar-toggler-wrapper">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"></div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <li class="<?php echo !$has_one_active ? 'active' : ''?>">
        <a href="<?php echo REAL_HOME ?>/admin">
            <i class="fa fa-dashboard"></i>
            <span class="title">Dashboard</span>
            <span class="selected"></span>
        </a>
    </li>
    <?php $topMenu = ZenView::get_menu('stick-actions') ?>
    <?php if ($topMenu['menu']): ?>
        <li>
            <a href="javascript:;">
                <i class="fa fa-bookmark"></i>
                <span class="title">Menu nhanh</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <?php if (is_array($topMenu['menu'])) foreach ($topMenu['menu'] as $item): ?>
                    <li>
                        <a href="<?php echo $item['full_url'] ?>" <?php echo $item['attr'] ?>>
                            <i class="<?php echo $item['icon'] ?>"></i> <?php echo $item['name'] ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </li>
    <?php endif ?>
    <?php $listModuleCP = get_extend_apps('admin/general/modulescp'); ?>
    <?php if ($listModuleCP): ?>
        <li>
            <a href="javascript:;">
                <i class="fa fa-cogs"></i>
                <span class="title">Bảng cài đặt</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <?php foreach ($listModuleCP as $item): ?>
                    <li><a href="<?php echo $item['full_url'] ?>"><?php echo $item['name'] ?></a></li>
                <?php endforeach ?>
            </ul>
        </li>
    <?php endif ?>
    <?php echo $content ?>
</ul>