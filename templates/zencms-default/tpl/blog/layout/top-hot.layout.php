<h2 class="no-top-space">Top xem nhiều</h2>
<div class="recent-news margin-bottom-10">
    <?php $list_hot = model()->list_hot_post(0, 'top_hot') ?>
    <?php foreach ($list_hot as $item): ?>
        <div class="row margin-bottom-10">
            <div class="col-md-3"><img src="<?php echo $item['full_icon'] ?>" class="img-responsive" alt="<?php echo $item['title'] ?>" /></div>
            <div class="col-md-9 recent-news-inner">
                <h3><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></h3>
                <p>Xem <?php echo $item['view'] ?></p>
            </div>
        </div>
    <?php endforeach ?>
</div>