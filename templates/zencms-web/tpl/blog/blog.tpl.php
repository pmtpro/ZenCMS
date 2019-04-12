<?php ZenView::load_layout('center/zen-slider')?>
<?php ZenView::load_layout('center/zen-events')?>
<div class="panel panel-default">
    <div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">TOP mới nhất</h3>
        </div>
        <div class="box"></div>
        <!--after-->
    </div>
    <div class="panel-body">
        <ul class="list-grid">
            <?php foreach (ZenView::$D['list']['new'] as $new): ?>
                <li class="col-xs-6 col-sm-3 col-md-2">
                    <span class="grid-item">
                      <a href="<?php echo $new['full_url'] ?>">
                        <span class="icon">
                          <img class="img-responsive" src="<?php echo $new['full_icon'] ?>" alt="<?php echo $new['title'] ?>">
                        </span>
                      </a>
                      <span class="info">
                        <span class="title">
                          <a href="<?php echo $new['full_url'] ?>" title="<?php echo $new['title'] ?>"><?php echo $new['name'] ?></a>
                        </span>
                      </span>
                      <span class="bottom">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải"><?php echo $new['view'] ?></span>
                      </span>
                    </span>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>

<?php $list_id = tplConfig('list_blog_cat_display') ?>
<?php if ($list_id) foreach ($list_id as $catID): ?>
    <?php $list = model('blog')->get_list_blog($catID, array('get' => 'url, name, title, time, view, icon', 'type' => 'post', 'limit' => tplConfig('num_post_per_box'), 'both_child' => true)) ?>
    <?php if (!empty($list)): ?>
        <?php $catData = model('blog')->get_blog_data($catID) ?>
        <div class="panel panel-default">
            <div class="panel-heading block-heading">
                <div class="box-tow">
                    <h3 class="panel-title block-title">
                        <a href="<?php echo $catData['full_url'] ?>" title="<?php echo $catData['title'] ?>"><?php echo $catData['name'] ?></a>
                    </h3>
                </div>
                <div class="box"></div>
                <a href="<?php echo $catData['full_url'] ?>" title="<?php echo $catData['title'] ?>" class="view-more">Xem thêm »</a>
                <!--after-->
            </div>
            <div class="panel-body">
                <ul class="list-grid">
                    <?php foreach ($list as $item): ?>
                        <li class="col-xs-6 col-sm-3 col-md-2">
                    <span class="grid-item">
                      <a href="<?php echo $item['full_url'] ?>">
                        <span class="icon">
                          <img class="img-responsive" src="<?php echo $item['full_icon'] ?>" alt="<?php echo $item['title'] ?>">
                        </span>
                      </a>
                      <span class="info">
                        <span class="title">
                          <a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a>
                        </span>
                      </span>
                      <span class="bottom">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải"><?php echo $item['view'] ?></span>
                      </span>
                    </span>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>