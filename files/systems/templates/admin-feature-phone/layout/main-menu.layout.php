<?php $application = ZenView::get_menu('application')?>
<?php
$content = '';
$has_active = false;
$has_one_active = false;
?>
<?php if(!empty($application['menu'])) foreach ($application['menu'] as $menu): ?>
    <?php $subContent = ''; $has_active = false; ?>
    <?php if(!empty($menu['sub_menus'])): ?>
        <?php $subContent = ''; ?>
        <?php foreach ($menu['sub_menus'] as $sub) {
            $active = false;
            if (is_child_page($sub['full_url'])) {$active = true; $has_active = true; $has_one_active = true;}
            $subContent .= '<option value="' . $sub['full_url'] . '"' . ($active?' selected':'') . '>- ' . $sub['name'] . '</option>';
        } ?>
    <?php endif ?>
    <?php $content .= '<option>' . $menu['name'] . '</option>' . $subContent;?>
<?php endforeach ?>

<select name="go" onchange="if (this.value) window.location.href=this.value">
    <option value="<?php echo HOME ?>/admin" <?php echo !$has_one_active ? 'selected' : ''?>>Dashboard</option>
    <?php $topMenu = ZenView::get_menu('stick-actions') ?>
    <?php if ($topMenu['menu']): ?>
        <option>Menu nhanh</option>
        <?php if (is_array($topMenu['menu'])) foreach ($topMenu['menu'] as $item): ?>
            <option value="<?php echo $item['full_url'] ?>">- <?php echo $item['name'] ?></option>
        <?php endforeach ?>
    <?php endif ?>
    <?php echo $content ?>
    <?php $listModuleCP = get_extend_apps('admin/general/modulescp'); ?>
    <?php if ($listModuleCP): ?>
        <option>Bảng cài đặt</option>
        <?php foreach ($listModuleCP as $item): ?>
            <option value="<?php echo $item['full_url'] ?>">- <?php echo $item['name'] ?></option>
        <?php endforeach ?>
    <?php endif ?>
</select>