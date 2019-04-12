<?php
$slider_config = tplConfig('slider_config');
$num_slider = count($slider_config);
$j=0;
?>
<?php if (!empty($slider_config)): ?>
    <div class="zen-slides">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> <!-- Indicators -->
        <ol class="carousel-indicators slider">
            <?php for ($i=0; $i<$num_slider; $i++): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i ?>" <?php echo ($i==0?' class="active"':'') ?>></li>
            <?php endfor ?>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <?php foreach($slider_config as $slider): ?>
                <?php $j++; ?>
                <div class="item <?php echo ($j==1?'active':'') ?>">
                    <a href="<?php echo $slider['url'] ?>"><img src="<?php echo $slider['img'] ?>" alt="slider"></a>
                </div>
            <?php endforeach ?>
        </div>
    </div> <!-- /slider -->
    </div>
<?php endif ?>