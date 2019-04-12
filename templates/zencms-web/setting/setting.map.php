<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
ZenView::block('Cấu hình blog', function() use ($registry) {
    ZenView::padded(function() use ($registry) {
        ZenView::display_message();
        $list_folders = $registry->model->get('blog')->get_tree_folder();
        $list_id = tplConfig('list_blog_cat_display', 'zencms-web');
        echo '<form role="form" class="form-horizontal validatable" method="POST">';
        echo '<div class="form-group">
        <label for="id_box_event" class="col-lg-2 control-label">Chọn box sự kiện</label>
        <div class="col-lg-9"><select id="id_box_event" name="id_box_event" class="chzn-select">';
        foreach($list_folders as $id=>$folder) {
            echo '<option value="' . $id . '" ' . ($id==tplConfig('id_box_event', 'zencms-web') ? 'selected':'') . '>' . $folder . '</option>';
        }
        echo '</select></div></div>';

        echo '<div class="form-group">
        <label for="list_blog_cat_display" class="col-lg-2 control-label">Danh sách box show ngoài trang chủ</label>
        <div class="col-lg-9"><select multiple="multiple" id="list_blog_cat_display" name="list_blog_cat_display[]" class="chzn-select">';
        foreach($list_folders as $id=>$folder) {
            echo '<option value="' . $id . '" ' . (in_array($id, $list_id) ? 'selected':'') . '>' . $folder . '</option>';
        }
        echo '</select></div></div>';

        echo '<div class="form-group">
        <label for="num_post_per_box" class="col-lg-2 control-label">Số bài đăng hiển thị trên 1 box</label>
        <div class="col-lg-9"><input type="text" id="num_post_per_box" name="num_post_per_box" value="' . tplConfig('num_post_per_box', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="id_post_hot" class="col-lg-2 control-label">ID bài viết hot nhất</label>
        <div class="col-lg-9"><input type="text" id="id_post_hot" name="id_post_hot" value="' . implode(',', tplConfig('id_post_hot', 'zencms-web')) . '" class="form-control"/>
        <div class="note pull-right">Cách nhau bằng dấu "," nên để khoảng 3 bài</div></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_top_new" class="col-lg-2 control-label">Số lượng bài viết top mới nhất</label>
        <div class="col-lg-9"><input type="text" id="num_post_top_new" name="num_post_top_new" value="' .  tplConfig('num_post_top_new', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_top_hot" class="col-lg-2 control-label">Số lượng bài viết hot nhất hiện thị bên trái</label>
        <div class="col-lg-9"><input type="text" id="num_post_top_hot" name="num_post_top_hot" value="' .  tplConfig('num_post_top_hot', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_post_in_folder" class="col-lg-2 control-label">Số lượng bài viết hiển thị trong thư mục</label>
        <div class="col-lg-9"><input type="text" id="num_post_in_folder" name="num_post_in_folder" value="' .  tplConfig('num_post_in_folder', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_rand_post_in_folder" class="col-lg-2 control-label">Số bài viết ngẫu nhiên trong thư mục</label>
        <div class="col-lg-9"><input type="text" id="num_rand_post_in_folder" name="num_rand_post_in_folder" value="' .  tplConfig('num_rand_post_in_folder', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_same_post_in_post" class="col-lg-2 control-label">Số bài viết tương tự trong bài viết</label>
        <div class="col-lg-9"><input type="text" id="num_same_post_in_post" name="num_same_post_in_post" value="' .  tplConfig('num_same_post_in_post', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <label for="num_rand_post_in_post" class="col-lg-2 control-label">Số bài viết ngẫu nhiên trong bài viết</label>
        <div class="col-lg-9"><input type="text" id="num_rand_post_in_post" name="num_rand_post_in_post" value="' .  tplConfig('num_rand_post_in_post', 'zencms-web') . '" class="form-control"/></div>
        </div>';
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-2"><button type="submit" name="submit-blog" class="btn btn-primary">Lưu thay đổi</button></div>
        </div>';
        echo '</form>';
    });
});

ZenView::block('Cài đặt slider', function() {
    ZenView::padded(function() {
        ZenView::display_message('slider');
        echo '<form role="form" class="form-horizontal validatable" method="POST">';
        $slider_config = tplConfig('slider_config', 'zencms-web');
        for ($i=0; $i<ZenView::$D['number_slider']; $i++) {
            $num = $i+1;
            echo '<div class="form-group">
        <label class="col-lg-2 control-label">Slider ' . $num . '</label>
        <div class="col-lg-9">
        <ul class="padded separate-sections">
            <li class="input">
                <label>URL</label><input class="form-control" type="text" name="url[]" value="' . (isset($slider_config[$i]['url'])?$slider_config[$i]['url']:'') . '" placeholder="http://"/>
            </li>
            <li class="input">
                <label>Ảnh</label><input class="form-control" type="text" name="img[]" value="' . (isset($slider_config[$i]['img'])?$slider_config[$i]['img']:'') . '" placeholder="http://"/>
            </li>
        </ul>
        </div></div>';
        }
        echo '<div class="form-group">
        <div class="col-lg-9 col-lg-offset-2"><button type="submit" name="submit-slider" class="btn btn-primary">Lưu thay đổi</button></div>
        </div>';
        echo '</form>';
    });
});