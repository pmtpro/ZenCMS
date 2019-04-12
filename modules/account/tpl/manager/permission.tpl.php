<?php load_header() ?>

    <h1 class="title border_orange">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="title border_blue"><?php echo $member['nickname'] ?></h2>

        <div class="tip">
            Bạn đang thay đổi quyền hạn cho thành viên <b><i><?php echo $member['nickname'] ?></i></b>
        </div>

        <div class="item">
            <form method="POST">
                <?php foreach ($permissions['name'] as $perm => $name): ?>
                    <label for="permission_<?php echo $perm ?>">
                        <div class="item">
                            <input type="radio" name="perm" id="permission_<?php echo $perm ?>"
                                   value="<?php echo $perm ?>" <?php if ($member['perm'] == $perm) echo 'checked'; ?> />
                            <?php echo $name ?>
                        </div>
                    </label>
                <?php endforeach ?>
                <input type="submit" name="sub_change" value="Lưu thay đổi" class="button BgBlue"/>
            </form>
        </div>
    </div>

<?php load_footer() ?>