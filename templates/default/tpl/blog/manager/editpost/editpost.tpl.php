<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="title border_blue">
            Sửa bài -
            <a href="<?php echo $blog['full_url'] ?>" target="_blank">
                <?php echo $blog['name'] ?>
            </a>
        </h2>

        <div class="tip">
            Bạn đang viết bài trong chế độ <b style="color:red"><?php echo $blog['type_data']; ?></b> -
            <u>
                <a href="<?php echo _HOME; ?>/blog/manager/editpost/<?php echo $blog['id']; ?>/step2/unset">
                    Thay đổi kiểu dữ liệu
                </a>
            </u>
        </div>

        <form method="POST">

            <div class="item">

                <select name="to">
                    <?php foreach ($tree_folder as $id => $name): ?>
                        <option value="<?php echo $id ?>" <?php if ($blog['parent'] == $id) echo 'selected' ?>>
                            <?php echo $name ?>
                        </option>
                    <?php endforeach ?>
                </select>

            </div>
            <div class="item">
                <input type="submit" name="sub_move" value="Di chuyển" class="button BgBlue"/>
            </div>

        </form>

        <form method="POST" enctype="multipart/form-data">

            <div class="item_manager"><?php echo icon('item'); ?> Tên:<br/>
                <input type="text" name="name" value="<?php echo $blog['name']; ?>" placeholder="Nhập tên bài viết"/>
            </div>

            <div class="item_manager">
                <?php echo icon('item'); ?> Title (Nằm trong thẻ title trang):<br/>

                <div class="tip">
                    Tiêu đề cũ: <b><?php echo $blog['title'] ?></b>
                </div>
                <label for="type_title_custom">
                    <input type="text" name="custom_title" value="<?php echo $blog['custom_title']; ?>"
                           placeholder="Tùy chỉnh tiêu đề"/><br/>
                </label>
                Hoặc:<br/>
                <label for="type_title_custom">
                    <input type="radio" name="type_title" value="custom"
                           id="type_title_custom" <?php echo $blog['check_title_custom']; ?> /> Tùy chỉnh<br/>
                </label>
                <label for="type_title_only_me">
                    <input type="radio" name="type_title" value="only_me"
                           id="type_title_only_me" <?php echo $blog['check_title_only_me']; ?> /> Chỉ tính title bài
                    này<br/>
                </label>
                <label for="type_title_with_parent">
                    <input type="radio" name="type_title" value="with_parent"
                           id="type_title_with_parent" <?php echo $blog['check_title_with_parent']; ?> /> Bao gồm cả
                    title thư mục trước<br/>
                </label>
                <label for="type_title_with_full_parent">
                    <input type="radio" name="type_title" value="with_full_parent"
                           id="type_title_with_full_parent" <?php echo $blog['check_title_with_full_parent']; ?> /> Bao
                    gồm tất cả title thư mục trước
                </label>
            </div>
            <div class="item_manager">
                <?php echo icon('item'); ?> Url:<br/>
                <label for="type_url_only_me">
                    <input type="radio" name="type_url" value="only_me"
                           id="type_url_only_me" <?php echo $blog['check_url_only_me']; ?> /> Chỉ tính url bài này<br/>
                </label>
                <label for="type_url_with_parent">
                    <input type="radio" name="type_url" value="with_parent"
                           id="type_url_with_parent" <?php echo $blog['check_url_with_parent']; ?> /> Bao gồm cả url 1
                    thư mục trước<br/>
                </label>
                <label for="type_url_with_full_parent">
                    <input type="radio" name="type_url" value="with_full_parent"
                           id="type_url_with_full_parent" <?php echo $blog['check_url_with_full_parent']; ?> /> Bao gồm
                    tất cả url thư mục trước
                </label>
            </div>
            <div class="item_manager">
                <?php echo icon('item'); ?> Nội dung:
                <div class="tip">
                    <label for="auto_get_img">
                        <input type="checkbox" name="auto_get_img" value="1" <?php echo gFormCache('auto_get_img', 'checked') ?> id="auto_get_img"/> Tự động lấy ảnh<br/>
                    </label>
                </div>
                <textarea name="content" style="width:100%;" rows="15"
                          id="content"><?php echo $blog['content']; ?></textarea>
            </div>

            <div class="item_manager"><?php echo icon('item'); ?> Keyword:<br/>
                <textarea name="keyword" style="width:100%;" rows="5"
                          placeholder="Phần keyword nằm trong thẻ meta keyword"><?php echo $blog['keyword']; ?></textarea>
            </div>
            <div class="item_manager"><?php echo icon('item'); ?> Mô tả:<br/>
                <textarea name="des" style="width:100%;" rows="5"
                          placeholder="Phần mô tả nằm trong thẻ meta description"><?php echo $blog['des']; ?></textarea>
            </div>
            <div class="item_manager"><?php echo icon('item'); ?> Tags:<br/>
                <textarea name="tags" style="width:100%;" rows="3"
                          placeholder="Mỗi tag cách nhau bằng dấu ,"><?php echo $blog['tags'] ?></textarea>
            </div>
            <div class="item_manager">
                <?php echo icon('item'); ?> Icon (Chọn 1 trong 2 cách sau):
                <div style="text-align: center;"><img src="<?php echo $blog['full_icon'] ?>"></div>
                <p>
                    <input type="file" name="file_icon" size="15"/><br/>
                    <input type="text" name="file_icon" size="30" placeholder="Hoặc nhập vào url hình ảnh"/><br/>
                    <label for="auto_resize_icon">
                        <input type="checkbox" name="auto_resize" value="1" <?php echo gFormCache('auto_resize', 'checked') ?> id="auto_resize_icon"/>Resize icon về kích
                        thước 80x80px
                    </label>
                </p>
            </div>
            <div class="item_manager">
                <input type="submit" id="sub_editpost" name="sub_editpost" value="Lưu thay đổi" class="button BgRed"/>
            </div>
        </form>

    </div>

<?php load_footer() ?>