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
            <?php if ($is_exists): ?>
                Module này đã có trong hệ thống.
            <?php endif ?>
            <?php if ($update): ?>
                <b style="color: #ff0000">Bạn có thể Update module này!</b><br/>
                Hệ thống không chắc chắn về hoạt động của module mới này. Bạn hãy lưu ý khi update!
            <?php endif ?>
        </div>
        <div class="info">
            <span class="text_smaller gray">
                - Tên: <?php echo $mod['url'] ?><br/>
                - ID: <?php echo $mod['id'] ?><br/>
                - <?php echo $mod['name'] ?><br/>
                - Phiên bản: <?php echo $mod['version'] ?><br/>
                - Tác giả: <?php echo $mod['author'] ?><br/>
                - Mô tả: <?php echo $mod['des'] ?><br/>
            </span>
        </div>

        <?php if ($is_exists): ?>
            <div class="info">
                <div class="item"><b>Phiên bản cũ:</b></div>
                <span class="text_smaller gray">
                    - Tên: <?php echo $o_mod['url'] ?><br/>
                    - ID: <?php echo $o_mod['id'] ?><br/>
                    - <?php echo $o_mod['name'] ?><br/>
                    - Phiên bản: <?php echo $o_mod['version'] ?><br/>
                    - Tác giả: <?php echo $o_mod['author'] ?><br/>
                    - Mô tả: <?php echo $o_mod['des'] ?><br/>
                </span>
            </div>
        <?php endif ?>

        <form method="POST">
            <div class="item">
                <input type="submit" name="sub_update" value="Update" class="button BgGreen"/>
                <input type="submit" name="sub_delete" value="Hủy" class="button BgRed"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>