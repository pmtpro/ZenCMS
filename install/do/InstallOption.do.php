<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Lựa chọn cài đặt</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Chọn cài đặt</span>
    </div>
    <div class="box-content padded">
        <?php load_message() ?>
        <div class="action-nav-normal action-nav-line">
            <div class="row action-nav-row">
                <div class="col-sm-6 action-nav-button">
                    <a href="<?php echo HOME ?>/install?do=DatabaseInfo" title="Cài đặt mới">
                        <i class="icon-folder-open-alt"></i>
                        <span>Cài đặt mới</span>
                    </a>
                    <span class="triangle-button red"><i class="icon-plus"></i></span>
                </div>
                <div class="col-sm-6 action-nav-button">
                    <a href="<?php echo HOME ?>/install?do=DatabaseInfo&next=UpgradeDatabase" title="Nâng cấp lên phiên bản mới">
                        <i class="icon-upload"></i>
                        <span>Nâng cấp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>