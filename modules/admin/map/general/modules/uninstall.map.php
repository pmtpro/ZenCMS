<?php
ZenView::section('Gỡ bỏ module', function() {
    ZenView::display_breadcrumb();
    ZenView::block('Xác nhận gỡ bỏ module', function() {
        echo '<form method="POST">';
        ZenView::display_message();
        echo('<div class="form-group">
            <input type="submit" name="submit-uninstall" value="Gỡ bỏ" class="btn btn-danger"/>
            <input type="submit" name="submit-cancel" value="Hủy" class="btn btn-default"/>
            </div>');
        echo '</form>';
    });
}, array('after'=>$menu));