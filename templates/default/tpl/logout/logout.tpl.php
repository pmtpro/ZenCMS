<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Đăng xuất</h1>

<?php load_message() ?>

    <div class="detail_content">
        <div class="notice">Bạn có muốn đăng xuất khỏi trang?</div>
        <form method="post">
            <div class="item">
                <input type="hidden" name="token_logout" value="<?php echo $token_logout; ?>"/>
                <input type="submit" name="sub" class="button BgRed" value="Thoát"/>
            </div>
        </form>
    </div>
<?php load_footer() ?>