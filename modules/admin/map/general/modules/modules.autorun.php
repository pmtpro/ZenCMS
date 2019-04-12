<?php
$page_menu = ZenView::get_menu('page_menu', true);
/**
 * menu_modules_controls hook*
 */
$page_menu['menu'] = hook('admin', 'menu_modules_controls', $page_menu['menu']);

if (!empty($page_menu['menu'])) {
    $menu = '<div class="btn-group">
      <button type="button" class="btn btn-primary" data-toggle="dropdown">
        <i class="fa fa-wrench"></i> Quản lí
      </button>
      <button class="btn btn-primary" data-toggle="dropdown"><span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" role="menu">';
    foreach ($page_menu['menu'] as $m):
        $menu .= '<li><a href="' . $m['full_url'] . '" title="' . $m['title'] . '" id="' . $m['id'] . '">' . (!empty($m['icon']) ? '<i class="' . $m['icon'] . '"></i>':'') . $m['name'] . '</a></li>';
    endforeach;
    $menu .= '</ul></div>';
}
