<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue">Menu</h2>

        <div class="content">
            <?php foreach ($menus as $url => $name): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $name ?></a>
            <?php endforeach ?>
        </div>
    </div>
<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <div class="tip">
            Hỗ trợ định dạng <?php echo $accept_format ?>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="item">
                <input type="file" name="module"/>
            </div>
            <div class="item">
                <input type="submit" name="sub_upload" value="Tải lên" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>