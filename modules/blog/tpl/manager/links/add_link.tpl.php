<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><a href="<?php echo $blog['full_url'] ?>"
                                             target="_blank"><?php echo $blog['name'] ?></a></h2>

        <div class="tip">Hãy điền tên link và link vào form bên dưới</div>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2" class="button BgBlue">Trở
                lại</a>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="title">Thêm link</h2>

        <form method="post">
            <div class="item_non_border">
                Tên Link:<br/>
                <input type="text" name="name"/>
            </div>
            <div class="item_non_border">
                Link:<br/>
                <input type="text" name="link"/>
            </div>
            <div class="item_non_border">
                <input type="hidden" name="token_add_link" value="<?php echo $token; ?>"/>
                <input type="submit" name="sub_add" value="Thêm link" class="button BgGreen"/>
            </div>
        </form>
    </div>
<?php load_footer(); ?>