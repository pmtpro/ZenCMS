<?php
ZenView::block('Xin chào!', function() use ($_client) {
    if (IS_MEMBER) {
        ZenView::load_layout('block/user', array('data'=>$_client));
    } else {
        ZenView::padded(function() {
            echo '<b><a href="' . HOME . '/login" title="Đăng nhập">Đăng nhập</a></b> hoặc <b><a href="' . HOME . '/register" title="Đăng kí">Đăng kí</a></b> để cùng tham gia với chúng tôi!';
        });
    }
});
$manager = ZenView::get_menu('manager');
if (!empty($manager)) {
    ZenView::block($manager['name'], function() use ($manager) {
        ZenView::load_layout('block/list-group-menu', array('data' => $manager['menu']));
    });
}

$main = ZenView::get_menu('main');
if (!empty($main)) ZenView::load_layout('block/list-group-menu', array('data' => $main['menu']));

$app = ZenView::get_menu('app');
if (!empty($app)) {
    ZenView::block($app['name'], function() use ($app) {
        ZenView::load_layout('block/list-group-menu', array('data' => $app['menu']));
    });
}
/***
 * Load widget
 */
widget_group('app');

$list = model('blog')->get_list_blog(null, array('get' => 'uid, url, name, title, time, view, icon', 'type' => 'post', 'order' => array('view'=>'DESC'), 'limit' => tplConfig('num_post_top_hot') ? tplConfig('num_post_top_hot') : 10));
if ($list) ZenView::block('TOP hot nhất', function() use ($list) {
    foreach ($list as $item) {
        $user = model('account')->get_user_data($item['uid'], 'username, nickname');
        echo '<div class="feed_rc_channel_item">
        <ul class="list-grid">
          <li>
            <a href="' . $item['full_url'] . '" title="' . $item['title'] . '">
              <img src="' . $item['full_icon'] . '" class="icon_img" alt="' . $item['title'] . '">
            </a>
          </li>
          <li style="width:100%;">
            <a href="' . $item['full_url'] . '" title="' . $item['title'] . '">
              <div class="title">' . $item['name'] . '</div>
            </a>
            <div class="subtitle">
              <i class="glyphicon glyphicon-eye-open"></i>  ' . $item['view'] . ' Xem
            </div>
            <div class="subtitle">
              bởi <a href="' . HOME . '/account/wall/' . $user['username'] . '" style="color:#e65757; font-weight:bold;">' . $user['nickname'] . '</a>
            </div>
          </li>
        </ul>
      </div>';
    }
});
