<div class="user_profile">
    <ul class="media-list">
        <li class="media">
            <a class="pull-left" href="<?php echo HOME ?>/account/wall/<?php echo ZenView::$layout_data['block/user']['username'] ?>">
                <img class="media-object" src="<?php echo ZenView::$layout_data['block/user']['full_avatar'] ?>" style="width:64px; border-radius:50%;" alt="avatar"/>
            </a>
            <div class="media-body">
                <h4 class="media-heading">
                    <a href="<?php echo HOME ?>/account/wall/<?php echo ZenView::$layout_data['block/user']['username'] ?>"><?php echo display_nick(ZenView::$layout_data['block/user']['nickname'], ZenView::$layout_data['block/user']['perm']) ?></a>
                    <?php echo hook(
                        'account', 'user_detail_box_after_name', '',
                        array('var' => array('user'=>$_client, 'wall'=>ZenView::$layout_data['block/user']))
                    )?>
                </h4>
                <p><i class="glyphicon glyphicon-user"></i> Member</p>
                <p>ID : <?php echo ZenView::$layout_data['block/user']['id'] ?></p>
                <?php echo hook(
                    'account', 'user_detail_box_list', '',
                    array('var' => array('user'=>$_client, 'wall'=>ZenView::$layout_data['block/user'], 'display'=>'<p>%s</p>'))
                )?>
            </div>
        </li>
        <?php echo hook(
            'account', 'user_detail_box_action', '',
            array(
                'end_callback' => function($out) {return (empty($out)) ? $out : '<li>' . $out . '</li>';},
                'var' => array('user'=>$_client, 'wall'=>ZenView::$layout_data['block/user'])
            )
        )?>
    </ul>
</div>