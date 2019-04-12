<?php load_header() ?>

    <div class="title border_blue">Tìm kiếm</div>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_orange">Nhập từ cần tìm</div>
        <?php load_message() ?>
        <form method="post" name="SearchPushUp">
            <div class="item">
                <input type="text" name="key" value="" placeholder="Nhập từ cần tìm"/>
            </div>
            <div class="item">
                <input type="submit" name="sub" value="Tìm kiếm" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php if (!empty($result)) : ?>
    <div class="detail_content">
        <h1 class="sub_title border_orange">Kết quả: <?php echo $page_title ?></h1>
        <?php foreach ($result as $s): ?>
            <div class="item">
                <?php echo icon('item') ?>
                <a href="<?php echo $s['full_url'] ?>" title="<?php echo $s['title'] ?>"><?php echo $s['name'] ?></a>
            </div>
        <?php endforeach ?>
        <?php echo $search_pagination ?>
    </div>
<?php endif ?>

<?php load_footer() ?>