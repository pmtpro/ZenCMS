<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    call_user_func(ZenView::$D['call_map']);
}, array('after'=>$menu));