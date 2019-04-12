<?php load_header(); ?>
<h1 class="title">Quản lí</h1>
<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

<div class="detail_content">
    <h2 class="sub_title border_blue">Nhập ID chuyên mục</h2>

    <div class="content">
        <div class="tip">Nhập ID chuyên mục hoặc bài viết bạn cần <b style="color:red">xóa</b> vào hoặc nhập trực tiếp
            Url mục đó vào đây
        </div>
        <form method="post" action="<?php echo _HOME ?>/blog/manager/delete">
            <input type="text" name="uri"/>
            <input type="submit" name="sub_step1" class="button BgRed" value="Tiếp tục"/>
        </form>
    </div>
</div>

<?php load_footer(); ?>
