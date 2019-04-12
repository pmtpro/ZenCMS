<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Sửa hồ sơ</h2>

        <?php load_message() ?>

        <form method="post">
            <div class="item">
                Tên thật:<br/>
                <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>"/>
            </div>
            <div class="item">
                Ngày sinh:<br>
                <input type="text" name="day" style="width:30px" value="<?php echo $user['birth_list']['day']; ?>">-
                <input type="text" name="month" style="width:30px" value="<?php echo $user['birth_list']['month']; ?>">-
                <input type="text" style="width:30px" name="year" value="<?php echo $user['birth_list']['year']; ?>">
            </div>
            <div class="item">
                Giới tính:<br>
                <select name="sex">
                    <option value="male" <?php echo $user['sex_selected_male'] ?> > Con trai</option>
                    <option value="female" <?php echo $user['sex_selected_female'] ?>> Con gái</option>
                    <option value="unknown" <?php echo $user['sex_selected_unknown'] ?>> Không xác định</option>
                </select>
            </div>
            <div class="item">
                Email:<br/>
                <input type="text" name="email" value="<?php echo $user['email']; ?>"/>
            </div>
            <div class="item">
                <input type="submit" name="sub_edit" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>