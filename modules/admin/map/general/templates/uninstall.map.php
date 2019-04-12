<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::block('Gỡ bỏ giao diện', function() {
        ZenView::display_message();
        echo '<form method="POST">
        <div class="form-group">
        <input type="submit" name="submit-uninstall" value="Xác nhận gỡ bỏ" class="btn btn-primary"/>
        <a href="' . HOME . '/admin/general/templates/detail/' . ZenView::$D['info']['package'] . '" class="btn btn-default">Hủy</a>
        </div>
        </form>';
    });
}, array('after' => $menu));