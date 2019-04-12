<?php load_header(); ?>
<h1 class="title">Quản lí</h1>
<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

<div class="detail_content">
    <h2 class="sub_title border_blue">Chuyển mục này vào thùng rác</h2>

    <div class="content">
        <div class="tip">
            Bạn có muốn chuyển
            <b><?php if ($blog['type'] == 'folder'): ?>Thư mục<?php else: ?>Bài Viết<?php endif; ?></b>
            <b><a href="<?php echo $blog['full_url']; ?>" target="_blank"><?php echo $blog['name']; ?></a></b> vào
            thùng rác?
        </div>
        <form method="post">
            <input type="hidden" name="token_confirm_delete" value="<?php echo $token; ?>"/>
            <input type="submit" name="sub_step2" class="button BgRed" value="Chuyển vào thùng rác"/>
            <a href="<?php echo _HOME ?>/blog/manager" class="button BgGreen">Hủy</a>
        </form>
    </div>
</div>
<?php load_footer(); ?>
