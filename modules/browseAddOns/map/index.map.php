<?php
$menu = '<div class="btn-group">
      <button type="button" class="btn btn-primary" data-toggle="dropdown">
        <i class="fa fa-wrench"></i> Quản lí
      </button>
      <button class="btn btn-primary" data-toggle="dropdown"><span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" role="menu">
      <li><a href="' . HOME . '/admin/general/modules"><i class="fa fa-puzzle-piece"></i> Quản lí module</a></li>
      <li><a href="' . HOME . '/admin/general/templates"><i class="fa fa-font"></i> Quản lí template</a></li>
      <li><a href="' . HOME . '/admin/general/config/#account-sync-config"><i class="fa fa-retweet"></i> Cài đặt đồng bộ</a></li>
      </ul></div>';

$btn_order = function($package) {
    if ($package->amount && !$package->paid && !$package->installed) {
        $btn = '<a href="' . $package->full_api_down . '" class="btn btn-warning" role="button" target="_blank"><span class="fa fa-shopping-cart"></span> ' . number_format($package->amount) . $package->currency . '</a>';
    } else {
        $btn_class = 'btn-primary';
        $icon = 'fa fa-flash';
        $attr = '';
        if ($package->installed && $package->updatable && (!$package->amount || $package->paid)) {
            $text = 'Nâng cấp';
            $btn_class = 'btn-success';
            $icon = 'fa fa-level-up';
        } elseif ($package->installed && $package->updatable && $package->amount && !$package->paid) {
            $text = 'Nâng cấp';
            $btn_class = 'btn-warning';
            $icon = 'fa fa-shopping-cart';
        } elseif ($package->installed && !$package->updatable) {
            $text = 'Đã cài đặt';
            $btn_class = 'btn-default';
            $attr = 'onmouseover="$(this).children(\'.btn-text\').html(\'Cài đặt lại\')" onmouseout="$(this).children(\'.btn-text\').html(\'' . $text . '\')"';
        } else {
            $text = 'Cài đặt';
        }
        $btn = '<a href="' . $package->full_api_down . '" class="btn ' . $btn_class . '" role="button" title="' . $text . '" ' . $attr . '><span class="' . $icon . '"></span> <span class="btn-text">' . $text . '</span></a>';
    }
    return $btn;
};
ZenView::section(ZenView::get_title(true), function() use ($btn_order) {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::col(function() use ($btn_order) {
        if (isset(ZenView::$D['package']) && ZenView::$D['package']) {
            ZenView::col_item(8, function() use ($btn_order) {
                ZenView::col(function() use ($btn_order) {
                    ZenView::col_item(4, function() use ($btn_order) {
                        echo '<div class="thumbnail">
                          <img src="' . ZenView::$D['package']->full_url_icon . '"/>
                          <div class="caption">
                            <h2 class="text-center">' . ZenView::$D['package']->name . '</h2>
                            <table class="table table-bordered">
                            <tr>
                                <td>Package ID</td>
                                <td>' . ZenView::$D['package']->pid . '</td>
                            </tr>
                            <tr>
                                <td>Kiểu</td>
                                <td>' . ZenView::$D['package']->type . '</td>
                            </tr>
                            <tr>
                                <td>Tác giả</td>
                                <td>' . ZenView::$D['package']->author . '</td>
                            </tr>
                            <tr class="info">
                                <td>Giá</td>
                                <td>' . (!ZenView::$D['package']->amount ? 'Miễn phí' : number_format(ZenView::$D['package']->amount) . ZenView::$D['package']->currency) . '</td>
                            </tr>
                            </table>
                            <p class="text-center">' . $btn_order(ZenView::$D['package']) . '</p>
                          </div>
                        </div>';
                    });
                    ZenView::col_item(8, function() use ($btn_order) {
                        ZenView::block('Giới thiệu', function() {
                            echo h_decode(ZenView::$D['package']->desc);
                        });

                        ZenView::block('Cài đặt', function() use ($btn_order) {
                            echo '<div class="list-group">';
                            foreach(ZenView::$D['package']->versions as $item) {
                                echo '<div class="list-group-item">
                                <div class="media">
                                ' . (!ZenView::$D['package']->amount ?
                                        '<a class="pull-left btn btn-success" href="' . genUrlAppFollow('browseAddOns') . '/install/' . $item->type . '/' . $item->pid  . '/' . $item->id . '" style="border-radius: 50% !important;" title="Cài đặt phiên bản này"><span class="fa fa-flash"></span></a>':
                                        (!ZenView::$D['package']->paid ?
                                            '<a class="pull-left btn btn-warning" href="' . genUrlAppFollow('browseAddOns') . '/purchase/' . $item->type . '/' . $item->pid . '" style="border-radius: 50% !important;" title="Mua" target="_blank"><span class="fa fa-shopping-cart"></span></a>' :
                                            '<a class="pull-left btn btn-warning" href="' . genUrlAppFollow('browseAddOns') . '/install/' . $item->type . '/' . $item->pid . '/' . $item->id . '" style="border-radius: 50% !important;" title="Cài đặt phiên bản này"><span class="fa fa-flash"></span></a>'
                                        )
                                    ) . '
                                <div class="media-body">
                                    <h4 class="media-heading">
                                    ' . (!ZenView::$D['package']->amount ?
                                        '<a href="' . genUrlAppFollow('browseAddOns') . '/install/' . $item->type . '/' . $item->pid  . '/' . $item->id . '" title="Cài đặt phiên bản này">' . $item->name . ' (Ver: ' . $item->version . ')</a>':
                                        (!ZenView::$D['package']->paid ?
                                            '<a href="' . genUrlAppFollow('browseAddOns') . '/purchase/' . $item->type . '/' . $item->pid . '" title="Mua" target="_blank">' . $item->name . ' (Ver: ' . $item->version . ')</a>' :
                                            '<a href="' . genUrlAppFollow('browseAddOns') . '/install/' . $item->type . '/' . $item->pid . '/' . $item->id . '" title="Cài đặt phiên bản này">' . $item->name . ' (Ver: ' . $item->version . ')</a>'
                                        )
                                    ) . '

                                    </h4>
                                    <p>lượt cài: ' . $item->down . ', đăng ngày: ' . m_timetostr($item->time) . '</p>
                                </div>
                                </div>
                                </div>';
                            }
                            echo '</div>';
                            echo '<p>
                                Để cài đặt add-on (module, template) cho website của mình, với những add-on miễn phí, các bạn chỉ việc click nút cài đặt, đối với những add-on mất phí, sau khi thanh toán thành công, hệ thống sẽ tự cài đặt cho các bạn.<br/>
                                Trong trường hợp các bạn tải trực tiếp add-on về thì các bạn truy cập vào trang cài đặt module hay template upload và cài đặt như bình thường.
                            </p>';
                        });
                    });
                });
            });
        }
        if (empty(ZenView::$D['package'])) {
            ZenView::col_item(8, function() use ($btn_order) {
                ZenView::block('<a href="' . ZenView::$D['base_url'] . '">Danh sách ' . (ZenView::$D['type'] ? ZenView::$D['type'] : 'Add-ons') . '</a>', function() use ($btn_order) {
                    ZenView::display_message('list-package');
                    if (is_array(ZenView::$D['packages'])) {
                        echo '<ul class="nav nav-tabs" role="tablist">
                          <li ' . (!ZenView::$D['type'] ? 'class="active"' : '') . '><a href="' . ZenView::$D['base_url'] . '">Tất cả</a></li>
                          <li ' . (ZenView::$D['type'] == 'module' ? 'class="active"' : '') . '><a href="' . ZenView::$D['base_url'] . '/module">Module</a></li>
                          <li ' . (ZenView::$D['type'] == 'template' ? 'class="active"' : '') . '><a href="' . ZenView::$D['base_url'] . '/template">Template</a></li>
                        </ul>';
                        echo '<div class="list-group">';
                        foreach (ZenView::$D['packages'] as $item) {
                            $packageUrl = ZenView::$D['base_url'] . '/' . $item->type . '/' . $item->pid;
                            echo '<div class="list-group-item active">';
                            echo '<div class="media">
                                <a class="pull-left" href="' . $packageUrl . '">
                                    <img class="media-object" src="' . $item->full_url_icon . '" alt="' . $item->name . '" style="max-width:100px"/>
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><a href="' . $packageUrl . '">' . $item->name . '</a></h4>
                                    <p><span class="text-muted"><span class="fa fa-clock-o"></span> ' . m_timetostr($item->time) . '</span><br/>' . h_decode($item->short_desc) . '</p>
                                    <p>
                                    <span class="label label-default"><span class="fa fa-user"></span> ' . $item->author . '</span>
                                    <span class="label label-warning"><span class="fa fa-shopping-cart"></span> ' . (empty($item->amount) ? 'Free': number_format($item->amount) . $item->currency) . '</span>
                                    </p>
                                </div>
                                <div class="pull-right">' . $btn_order($item) . '</div>
                            </div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    ZenView::display_paging('list-package');
                });
            });
        }
        if (ZenView::$D['top_packages']) ZenView::col_item(4, function() use ($btn_order) {
            ZenView::block('Download nhiều nhất', function () use ($btn_order) {
                echo '<div class="list-group">';
                foreach (ZenView::$D['top_packages'] as $item) {
                    $packageUrl = ZenView::$D['base_url'] . '/' . $item->type . '/' . $item->pid;
                    echo '<div class="list-group-item">';
                    echo '<div class="media">
                            <a class="pull-left" href="' . $packageUrl . '">
                                <img class="media-object" src="' . $item->full_url_icon . '" alt="' . $item->name . '" style="max-width:80px"/>
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="' . $packageUrl . '">' . $item->name . '</a></h4>
                                <p>' . h_decode($item->short_desc) . '</p>
                            </div>
                            <div class="pull-right">' . $btn_order($item) . '</div>
                        </div>';
                    echo '</div>';
                }
                echo '</div>';
            });
        });
    });
}, array('after' => $menu));