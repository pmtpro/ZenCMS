<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"> <?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title; ?></h2>

        <form method="POST">

            <div class="tip">
                Bạn có chắc chắn xóa bài này?
            </div>

            <div class="item">
                <input type="submit" name="sub_delete" value="Xóa" class="button BgRed"/>
                <a href="<?php echo _HOME ?>/chatbox" class="button BgGreen">Hủy</a>
            </div>

        </form>
    </div>
<?php load_footer() ?>