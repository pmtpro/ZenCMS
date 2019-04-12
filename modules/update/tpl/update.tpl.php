<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Update From 310 to 400</h1>

<?php load_message() ?>

    <div class="tip">Click vào nút dưới để bắt đầu update</div>
    <form method="post">
        <div class="item">
            <input type="submit" name="sub" value="Click here to start update" class="button BgGreen"/>
        </div>
    </form>

    <div class="sub_title border_orange">Các công đoạn sẽ thực hiện</div>

<?php foreach ($steps as $id => $step): ?>
    <div class="item">
        <?php echo icon('item'); ?> <?php echo $step['name']; ?> <?php if ($step['process']): ?><b style="color: red;">OK!</b><?php endif; ?>
    </div>
<?php endforeach; ?>

<?php load_footer() ?>