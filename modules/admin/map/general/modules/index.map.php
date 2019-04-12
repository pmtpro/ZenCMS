<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    $content_after = '<span class="label label-info">' . count(ZenView::$D['modules']) . '</span>';
    ZenView::block('Danh sách module', function() {
        echo '<div class="table-responsive"><table class="table table-hover table-bordered table-striped">';
        echo'<thead><tr>
        <th><div></div></th>
        <th><div>Tên</div></th>
        <th><div>URL</div></th>
        <th><div>Phiên bản</div></th>
        <th><div>Tác giả</div></th>
        <th><div>Mô tả</div></th>
        </tr></thead>';
        foreach (ZenView::$D['modules'] as $mod) {
            echo '<tr><td>
            <div class="btn-group">
            <button class="btn btn-xs btn-' . ($mod['activated'] ? 'primary':'default') . ' dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i></button>
            <ul class="dropdown-menu">';
            foreach ($mod['actions'] as $act) {
                if ($act['divider']) {
                    echo '<li class="divider"></li>';
                }
                echo '<li><a href="' . $act['full_url'] . '" title="' . $act['title'] . '">' .(!empty($act['icon']) ? '<i class="' . $act['icon'] . '"></i> ' : '') . $act['name'] . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo('</td>
            <td>' . $mod['name'] . '</td>
            <td>' . $mod['url'] . '</td>
            <td>' . $mod['version'] . '</td>
            <td>' . $mod['author'] . '</td>
            <td>' . $mod['des'] . '</td>
            </tr>');
        }
        echo '</table></div>';
        echo '<div class="row"><div class="col-md-12">
        <div class="pull-right"><form method="POST">
        <input type="submit" name="reloadAllModule" id="reloadAllModule" value="Reload" class="btn btn-primary rm-fill-up"/>
        </form></div>
        </div></div>';
    }, array('after' => $content_after));
}, array('after' => $menu));