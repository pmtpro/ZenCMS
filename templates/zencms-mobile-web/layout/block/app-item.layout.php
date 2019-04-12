<div class="row channel_item">
    <div class="col-xs-12">
        <ul class="u_table u_table_top">
            <li style="padding:10px;">
                <a href="<?php echo ZenView::$layout_data['block/app-item']['full_url'] ?>" title="<?php echo ZenView::$layout_data['block/app-item']['title'] ?>">
                    <div class="thumb">
                        <div style="display:block; position:relative;">
                            <div class="overlay"></div>
                            <img src="<?php echo ZenView::$layout_data['block/app-item']['full_icon'] ?>" alt="<?php echo ZenView::$layout_data['block/app-item']['title'] ?>" class="thumb_img">
                        </div>
                    </div>
                    <!-- /.thumb -->
                </a>
            </li>
            <li class="channel_item_detail">
                <div class="title">
                    <a href="<?php echo ZenView::$layout_data['block/app-item']['full_url'] ?>" title="<?php echo ZenView::$layout_data['block/app-item']['title'] ?>"><?php echo ZenView::$layout_data['block/app-item']['name'] ?></a>
                    <span class="paragraph-end" style="height:20px; top:20px;"></span>
                </div>
                <div class="info14">
                    <?php $cat = model('blog')->get_blog_data(ZenView::$layout_data['block/app-item']['parent']) ?>
                    Thể loại: <a href="<?php echo $cat['full_url'] ?>"><?php echo $cat['name'] ?></a>
                </div>
            </li>
            <li style="padding-top:10px;">
            </li>
        </ul>
        <?php if (ZenView::$layout_data['block/app-item']['des']): ?>
            <div class="desc">
                <span class="paragraph-end" style="height:18px; width:86px; top:36px;"></span>
                <?php echo ZenView::$layout_data['block/app-item']['des'] ?>
            </div>
        <?php endif ?>
        <div class="info14" style="padding:0px 10px 6px 10px">
            <?php echo ZenView::$layout_data['block/app-item']['display_time'] ?>
        </div>
        <div class="bottom">
            <ul class="u_table">
                <?php $user = model('account')->get_user_data(ZenView::$layout_data['block/app-item']['uid'], 'username, nickname, avatar') ?>
                <li>
                    <span class="avatar"><img src="<?php echo $user['full_avatar'] ?>" class="avatar_img" alt="avatar"/></span>
                </li>
                <li style="width:100%;">
                    <a href="<?php echo HOME ?>/account/wall/<?php echo $user['username'] ?>" class="guru_name"><b><?php echo $user['nickname'] ?></b></a></li>
                <li>
                    <a href="<?php echo ZenView::$layout_data['block/app-item']['full_url'] ?>#download"><img src="<?php echo _BASE_TEMPLATE ?>/theme/images/icons/download.jpg"/></a>
                </li>
            </ul>
        </div>
        <!-- /.bottom -->
    </div>
    <!-- /.col-xs-12 -->
</div>