<?php load_header(); ?>
<h1 class="title">Quản lí</h1>
<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

<div class="detail_content">
    <h2 class="sub_title border_blue">Nhập ID bài viết</h2>

    <div class="content">
        <div class="tip">Nhập ID bài viết hoặc nhập trực tiếp Url bài đó vào đây để sửa</div>
        <form method="post">
            <input type="text" name="uri"/>
            <input type="submit" name="sub_step1" class="button BgBlue" value="Tiếp tục"/>
        </form>
    </div>
</div>
<?php load_footer(); ?>
