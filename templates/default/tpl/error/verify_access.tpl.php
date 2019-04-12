<?php load_header() ?>

    <div class="detail_content">
        <h1 class="sub_title border_blue">Verification Code</h1>

        <?php load_message() ?>

        <form method="POST">
            <div class="item">
                <input type="password" name="zen_verity_access" value="" />
            </div>
            <div class="item">
                <input type="submit" name="sub_verify" class="button BgBlack" value="Continue"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>