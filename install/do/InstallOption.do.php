<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 3</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Lựa chọn cài đặt</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(3) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block text-center">Lựa chọn cài đặt</h3>
                                    <?php load_message() ?>

                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                                    <a class="dashboard-stat blue-madison" href="<?php echo HOME ?>/?do=DatabaseInfo">
                                                        <div class="visual">
                                                            <i class="fa fa-flash"></i>
                                                        </div>
                                                        <div class="details">
                                                            <div class="number">
                                                                <?php echo ZENCMS_VERSION ?>
                                                            </div>
                                                            <div class="desc">
                                                                Cài mới ZenCMS
                                                            </div>
                                                        </div>
                                                        <span class="more">
                                                            Cài đặt mới <i class="m-icon-swapright m-icon-white"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                                    <a class="dashboard-stat red-intense" href="<?php echo HOME ?>/?do=DatabaseInfo&next=UpgradeDatabase">
                                                        <div class="visual">
                                                            <i class="fa fa-level-up"></i>
                                                        </div>
                                                        <div class="details">
                                                            <div class="number">
                                                                <?php echo ZENCMS_VERSION ?>
                                                            </div>
                                                            <div class="desc">
                                                                Nâng cấp lên <?php echo ZENCMS_VERSION ?>
                                                            </div>
                                                        </div>
                                                        <span class="more">
                                                            Nâng cấp <i class="m-icon-swapright m-icon-white"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="<?php echo HOME ?>?do=CheckSystem" class="btn btn-default pull-left"><span class="fa fa-arrow-left"></span> Trở lại</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>