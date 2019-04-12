<?php
ZenView::section('Danh sách widget', function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    echo '<form method="POST">';
    foreach (ZenView::$D['widget_groups'] as $group) {
        ZenView::block(ZenView::$D['widget_list'][$group]['config']['desc'] ? ZenView::$D['widget_list'][$group]['config']['desc'] . ' (<i class="smaller">' . $group . '</i>)' : $group, function() use ($group) {
            ZenView::padded(function() use ($group) {
                if (isset(ZenView::$D['widget_groups_data'][$group]) && is_array(ZenView::$D['widget_groups_data'][$group])) {
                    $groupData = ZenView::$D['widget_groups_data'][$group];
                    echo '<div class="row">';
                    foreach ($groupData as $widget) {
                        echo '<div class="col-sm-6 col-md-4">
                        <div class="thumbnail text-center">
                          <div class="caption">
                            <h3><a href="' . ZenView::$D['base_url'] . '?act=edit&id=' . $widget['id'] . '">' . ($widget['title']? $widget['title'] : '<i class="smaller">Không tiêu đề</i>') . '</a></h3>
                            <p><input type="text" name="weight[' . $widget['id'] . ']" class="text-center" style="width: 30px" value="' . $widget['weight'] . '"/> </p>
                            <p>
                            <a href="' . ZenView::$D['base_url'] . '?act=delete&id=' . $widget['id'] . '" class="btn btn-default" role="button"><i class="fa fa-trash-o"></i> Xóa</a>
                            <a href="' . ZenView::$D['base_url'] . '?act=edit&id=' . $widget['id'] . '" class="btn btn-primary" role="button"><i class="fa fa-pencil"></i> Sửa</a>
                            </p>
                          </div>
                        </div>
                      </div>';
                    }
                    echo '</div>';
                } else echo '<i class="smaller">Chưa có widget nào ở đây</i>';
            });
            echo '<div class="row">
            <div class="col-md-12">
            <div class="pull-right">
            <input type="submit" name="submit-order[' . $group . ']" value="Sắp xếp" class="btn btn-primary rm-fill-up"/>
            </div>
            </div>
            </div>';
        }, array('after' => '<a href="' . ZenView::$D['base_url'] . '?act=new&wg=' . urlencode($group) . '"><i class="fa fa-plus"></i> Thêm mới</a>'));
    }
    echo '</form>';
}, array('after' => $menu));