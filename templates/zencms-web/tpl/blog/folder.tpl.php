<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title title-info"><?php echo ZenView::$D['blog']['name'] ?></h1>
    </div>
    <div class="panel-body">
        <?php ZenView::display_breadcrumb(); ?>
        <?php if (!empty(ZenView::$D['blog']['content'])): ?>
            <div class="app_desc">
                <?php echo ZenView::$D['blog']['content'] ?>
            </div>
        <?php endif ?>
        <ul class="list-grid">
            <?php foreach (ZenView::$D['list']['posts'] as $item): ?>
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
        <div class="padded"><?php ZenView::display_paging('post') ?></div>
    </div>
</div>

<?php if (ZenView::$D['list']['rand_posts']): ?>
    <!-- List rand post -->
    <div class="panel panel-default">
        <div class="panel-heading block-heading">
            <div class="box-tow">
                <h3 class="panel-title block-title">Bài viết ngẫu nhiên</h3>
            </div>
            <div class="box"></div>
        </div>
        <div class="panel-body">
        <ul class="list-grid">
            <?php foreach (ZenView::$D['list']['rand_posts'] as $item): ?>
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
    <!-- End List rand post -->
<?php endif ?>