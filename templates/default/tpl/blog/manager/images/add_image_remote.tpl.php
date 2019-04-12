<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><a href="<?php echo $blog['full_url'] ?>"
                                             target="_blank"><?php echo $blog['name'] ?></a></h2>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2" class="button BgBlue">Trở lại</a>
            <a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2/add" class="button BgGreen">Tải lên từ máy</a>

        </div>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_red">Nhập url hình ảnh</h3>

        <form method="post" enctype="multipart/form-data">
            <div class="item_non_border">
                <input type="submit" name="sub_add" value="Tải ảnh lên" class="button BgGreen"/>
            </div>
            <?php foreach ($form_images as $image): ?>
                <div class="list">
                    <input type="text" name="link[]" placeholder="http://"/><br/>
                </div>
            <?php endforeach; ?>
            <div class="item_non_border">
                <input type="hidden" name="token_add_image" value="<?php echo $token; ?>"/>
                <input type="submit" name="sub_add" value="Tải ảnh lên" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer(); ?>