<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><a href="<?php echo $blog['full_url'] ?>"
                                             target="_blank"><?php echo $blog['name'] ?></a></h2>

        <div class="tip">Bạn đang xóa link <b><a href="<?php echo $link['link'] ?>"
                                                 target="_bank"><?php echo $link['name'] ?></a></b></div>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2/edit/<?php echo $link['id'] ?>"
               class="button BgBlue">Trở lại</a>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="title">Xóa link</h2>

        <form method="post">
            <div class="item_non_border">
                Bạn có muốn xóa link này? Tất cả dữ liệu liên quan đến link sẽ bị xóa
            </div>
            <div class="item_non_border">
                <input type="hidden" name="token_delete_link" value="<?php echo $token ?>"/>
                <input type="submit" name="sub_delete" value="Đồng ý xóa" class="button BgRed"/>
                <a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2"
                   class="button BgBlue">Hủy</a>
            </div>
        </form>
    </div>

<?php load_footer(); ?>