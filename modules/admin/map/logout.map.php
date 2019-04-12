<?php
ZenView::section('Đăng xuất', function() {
    ZenView::block('Bạn có chắc chắn muốn thoát?', function() {
        ZenView::padded(function() {
            ZenView::display_message();
            echo('<form method="POST">
                <div class="zen-form-group">
                    <input type="hidden" name="token-logout" value="' . ZenView::$D['token-logout'] . '"/>
                    <input type="submit" name="submit-logout" class="btn btn-primary" value="Thoát"/>
                </div>
            </form>');
        });
    });
});