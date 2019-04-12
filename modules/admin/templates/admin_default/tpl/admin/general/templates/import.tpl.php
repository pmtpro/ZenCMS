<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>
<?php load_message() ?>

    <div class="detail_content">
        <div class="sub_title border_blue">Tải lên giao diện</div>
        <div class="tip">
            Hỗ trợ định dạng <?php echo $accept_format ?>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="item">
                <input type="file" name="template" accept="" />
            </div>
            <div class="item">
                <input type="submit" name="sub" value="Tải lên" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>