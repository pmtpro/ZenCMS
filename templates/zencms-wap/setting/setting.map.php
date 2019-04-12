<?php
ZenView::block('Cấu hình blog', function() use ($registry) {
    ZenView::padded(function() use ($registry) {
        ZenView::display_message();
        $list_folders = $registry->model->get('blog')->get_tree_folder();
        $list_id = tplConfig('list_blog_cat_display', 'zencms-wap');
        echo '<form role="form" class="form-horizontal validatable" method="POST">';

        echo '<div class="form-group">
        <label for="list_blog_cat_display" class="col-lg-2 control-label">Danh sách box show ngoài trang chủ</label>
        <div class="col-lg-9"><select multiple="multiple" id="list_blog_cat_display" name="list_blog_cat_display[]" class="chzn-select">';
        foreach($list_folders as $id=>$folder) {
            echo '<option value="' . $id . '" ' . (in_array($id, $list_id) ? 'selected':'') . '>' . $folder . '</option>';
        }
        echo '</select></div></div>';

        echo '<div class="form-group">
        <label for="num_post_per_box" class="col-lg-2 control-label">Số bài đăng hiển thị trên 1 box</label>
        <div class="col-lg-9"><input type="text" id="num_post_per_box" name="num_post_per_box" value="' . tplConfig('num_post_per_box', 'zencms-wap') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="id_post_hot" class="col-lg-2 control-label">ID bài viết hot nhất</label>
        <div class="col-lg-9"><input type="text" id="id_post_hot" name="id_post_hot" value="' . implode(',', tplConfig('id_post_hot', 'zencms-wap')) . '" class="form-control"/>
        <div class="note pull-right">Cách nhau bằng dấu "," nên để khoảng 3 bài</div></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_top_hot" class="col-lg-2 control-label">Số lượng bài viết mới nhất</label>
        <div class="col-lg-9"><input type="text" id="num_post_top_new" name="num_post_top_new" value="' .  tplConfig('num_post_top_new', 'zencms-wap') . '" class="form-control"/></div>
        </div>';

        echo '<div class="form-group">
        <label for="num_post_in_folder" class="col-lg-2 control-label">Số lượng bài viết hiển thị trong thư mục</label>
        <div class="col-lg-9"><input type="text" id="num_post_in_folder" name="num_post_in_folder" value="' .  tplConfig('num_post_in_folder', 'zencms-wap') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_rand_post_in_folder" class="col-lg-2 control-label">Số bài viết ngẫu nhiên trong thư mục</label>
        <div class="col-lg-9"><input type="text" id="num_rand_post_in_folder" name="num_rand_post_in_folder" value="' .  tplConfig('num_rand_post_in_folder', 'zencms-wap') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_same_post_in_post" class="col-lg-2 control-label">Số bài viết tương tự trong bài viết</label>
        <div class="col-lg-9"><input type="text" id="num_same_post_in_post" name="num_same_post_in_post" value="' .  tplConfig('num_same_post_in_post', 'zencms-wap') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_rand_post_in_post" class="col-lg-2 control-label">Số bài viết ngẫu nhiên trong bài viết</label>
        <div class="col-lg-9"><input type="text" id="num_rand_post_in_post" name="num_rand_post_in_post" value="' .  tplConfig('num_rand_post_in_post', 'zencms-wap') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-2"><button type="submit" name="submit-blog" class="btn btn-primary">Lưu thay đổi</button></div>
        </div>';
        echo '</form>';
    });
});
