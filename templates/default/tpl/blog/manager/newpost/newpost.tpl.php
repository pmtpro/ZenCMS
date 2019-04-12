<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">

        <h2 class="title border_red">Viết bài - <a href="<?php echo $cat['full_url'] ?>"
                                                   target="_blank"><?php echo $cat['name'] ?></a></h2>

        <div class="tip">Bạn đang viết bài trong chế độ <b style="color:red"><?php echo $type_data; ?></b> -
            <u><a href="<?php echo _HOME; ?>/blog/manager/newpost/<?php echo $cid; ?>/step2/unset">Thay đổi kiểu dữ
                    liệu</a></u></div>

        <form method="POST" enctype="multipart/form-data">
            <div class="item_manager">
                <?php echo icon('item'); ?> Tên:<br/>
                <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"
                       placeholder="Nhập tên bài viết"/>
            </div>
            <div class="item_manager">
                <?php echo icon('item'); ?> Title (Nằm trong thẻ title trang):<br/>

                <div class="tip">
                    Bỏ trống để lấy title trùng tên
                </div>
                <label for="type_title_custom">
                    <input type="text" name="custom_title"
                           value="<?php echo isset($_POST['custom_title']) ? $_POST['custom_title'] : ''; ?>"
                           placeholder="Tùy chỉnh tiêu đề"/><br/>
                </label>
                Hoặc:<br/>
                <label for="type_title_custom">
                    <input type="radio" name="type_title" value="only_me" id="type_title_custom" checked/> Tùy
                    chỉnh<br/>
                </label>
                <label for="type_title_only_me">
                    <input type="radio" name="type_title" value="only_me" id="type_title_only_me" checked/> Chỉ tính
                    title bài này<br/>
                </label>
                <label for="type_title_with_parent">
                    <input type="radio" name="type_title" value="with_parent" id="type_title_with_parent"/> Bao gồm cả
                    title thư mục trước<br/>
                </label>
                <label for="type_title_with_full_parent">
                    <input type="radio" name="type_title" value="with_full_parent" id="type_title_with_full_parent"/>
                    Bao gồm tất cả title thư mục trước
                </label>
            </div>
            <div class="item_manager">
                <?php echo icon('item'); ?> Url:<br/>
                <label for="type_url_only_me">
                    <input type="radio" name="type_url" value="only_me" id="type_url_only_me" checked/> Chỉ tính url bài
                    này<br/>
                </label>
                <label for="type_url_with_parent">
                    <input type="radio" name="type_url" value="with_parent" id="type_url_with_parent"/> Bao gồm cả url 1
                    thư mục trước<br/>
                </label>
                <label for="type_url_with_full_parent">
                    <input type="radio" name="type_url" value="with_full_parent" id="type_url_with_full_parent"/> Bao
                    gồm tất cả url thư mục trước
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
                          id="content"><?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?></textarea>
            </div>

            <div class="item_manager"><?php echo icon('item'); ?> Keyword:<br/>
                <textarea name="keyword" style="width:100%;" rows="5"
                          placeholder="Phần keyword nằm trong thẻ meta keyword"><?php echo isset($_POST['keyword']) ? $_POST['keyword'] : ''; ?></textarea>
            </div>
            <div class="item_manager"><?php echo icon('item'); ?> Mô tả:<br/>
                <textarea name="des" style="width:100%;" rows="5"
                          placeholder="Phần mô tả nằm trong thẻ meta description"><?php echo isset($_POST['des']) ? $_POST['des'] : ''; ?></textarea>
            </div>
            <div class="item_manager"><?php echo icon('item'); ?> Tags:<br/>
                <textarea name="tags" style="width:100%;" rows="3"
                          placeholder="Mỗi tag cách nhau bằng dấu ,"><?php echo isset($_POST['tags']) ? $_POST['tags'] : ''; ?></textarea>
            </div>

            <div class="item_manager">
                <?php echo icon('item'); ?> Icon (Chọn 1 trong 2 cách sau):<br/>

                <p>
                    <input type="file" name="file_icon" size="15"/><br/>
                    <input type="text" name="file_icon" size="30" placeholder="Hoặc nhập vào url hình ảnh"/><br/>
                    <label for="auto_resize_icon">
                        <input type="checkbox" name="auto_resize" value="1" <?php echo gFormCache('auto_resize', 'checked') ?> id="auto_resize_icon"/> Resize icon về kích
                        thước 80x80px
                    </label>
                </p>
            </div>
            <div class="item_manager"><input type="submit" id="sub_newpost" name="sub_newpost" value="Viết bài"
                                             class="button BgRed"/></div>
        </form>

    </div>

<?php load_footer() ?>