<?php $box_event_data = model('blog')->get_blog_data(tplConfig('id_box_event'), 'url,name,title');?>
<?php $list_event = model('blog')->get_list_blog(tplConfig('id_box_event'), array('get' => 'url, name, title, time, view, icon', 'type'=>'post', 'both_child' => true, 'limit' => 5)) ?>
<div class="row events">
    <div class="col-md-6 left">
        <h4>
            <span class="event-title"><a href="<?php echo $box_event_data['full_url'] ?>" title="<?php echo $box_event_data['title'] ?>"><?php echo $box_event_data['name'] ?></a></span>
        </h4>
        <ul class="hover">
            <?php if ($list_event) foreach($list_event as $event): ?>
                <li>
                    <span>●</span>
                    <a href="<?php echo $event['full_url'] ?>" title="<?php echo $event['title'] ?>">
                        <?php echo $event['name'] ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="col-md-6 right">
        <ul class="show_right">
            <?php $list_hot = tplConfig('id_post_hot'); $i=0 ?>
            <?php if ($list_hot) foreach ($list_hot as $idHot): ?>
                <?php $postData = model('blog')->get_blog_data($idHot, 'url,name,title,icon') ?>
                <?php $i++; if ($i == 1):?>
                    <li class="first">
                        <a class="threadDetailFirstImg" href="<?php echo $postData['full_url'] ?>" title="<?php echo $postData['title'] ?>">
                            <img src="<?php echo $postData['full_icon'] ?>">
                        </a>
                        <a class="threadDetailFirstTitle" href="<?php echo $postData['full_url'] ?>" title="<?php echo $postData['title'] ?>"><?php echo $postData['name'] ?></a>
                    </li>
                <?php else: ?>
                    <li class="normal">
                        <a href="<?php echo $postData['full_url'] ?>" title="<?php echo $postData['title'] ?>">
                            <?php echo $postData['name'] ?>
                        </a>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
</div>