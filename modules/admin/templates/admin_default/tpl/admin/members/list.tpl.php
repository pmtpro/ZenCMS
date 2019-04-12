<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí thành viên</div>
        <div class="item">
            <form method="GET">
                <select name="filter">
                    <option value="" <?php if (empty($filter)) echo 'selected'; ?>>Tất cả</option>
                    <?php foreach ($permissions['name'] as $perm => $name): ?>
                        <option value="<?php echo $perm ?>" <?php if ($filter == $perm) echo 'selected'; ?>>
                            <?php echo $name ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <input type="submit" value="Lọc" class="button BgBlue"/>
            </form>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Danh sách lọc</div>
        <?php if (empty($users)): ?>

            <div class="tip">
                Không có thành viên nào trong danh sách này
            </div>

        <?php else: ?>

            <?php foreach ($users as $user): ?>

                <div class="item">
                    <?php echo icon('item') ?>
                    <a href="<?php echo _HOME ?>/admin/members/user/<?php echo $user['username'] ?>"><?php echo $user['nickname'] ?></a>
                </div>

            <?php endforeach ?>

            <?php echo $users_pagination ?>

        <?php endif ?>
    </div>

<?php load_footer() ?>