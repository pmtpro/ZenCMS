<div class="container">
    <ul class="pull-left">
        <li>Power by <a href="http://zencms.vn" target="_blank">ZenCMS</a></li>
        <li>Version: <?php echo ZENCMS_VERSION ?></li>
        <?php echo phook('copyright', '', array('callback' => function($item) { return '<li>' . $item . '</li>';})) ?>
    </ul>
    <ul class="pull-right">
        <?php echo phook('footer_controls', '', array('callback' => function($item) { return '<li>' . $item . '</li>';})) ?>
    </ul>
</div>