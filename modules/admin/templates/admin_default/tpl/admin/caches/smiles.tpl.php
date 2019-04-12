<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Update smile</div>
        <?php load_message() ?>
        <form method="post">
            <div class="item">
                <input type="submit" name="sub_reload_smiles_cache" value="Update smile" class="button BgGreen"/>
            </div>
        </form>
    </div>
<?php load_footer() ?>