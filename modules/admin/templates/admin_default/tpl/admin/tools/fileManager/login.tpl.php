<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>

        <?php load_message() ?>

        <form method="POST">
            <div class="item">
                Mã xác nhận:<br/>
                <input type="password" name="passwordAccess" value="" />
            </div>
            <div class="item">
                <input type="submit" name="sub_verify" value="Tiếp tục"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>