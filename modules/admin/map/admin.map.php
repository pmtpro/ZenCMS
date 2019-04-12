<?php
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::block('New feed', function() {
        ZenView::display_message('new_feed');
        if (ZenView::$D['new_feeds']) foreach (ZenView::$D['new_feeds'] as $item) {
            echo '<div class="media">
              <a class="pull-left" href="' . $item->full_url . '" target="_blank">
                <img class="media-object" src="' . $item->full_icon . '" style="max-width: 150px"/>
              </a>
              <div class="media-body">
                <h4 class="media-heading"><a href="' . $item->full_url . '" target="_blank">' . $item->name . '</a></h4>
                <p>' . $item->short_desc . '</p>
              </div>
            </div>';
        }
    });
});