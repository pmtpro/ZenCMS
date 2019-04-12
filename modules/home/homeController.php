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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

Class homeController Extends ZenController
{

    public function index()
    {
        $data['thank'] = 'Cảm ơn bạn đã chọn ZenCMS!';
        $data['step'][] = 'Click <b><a href="' . HOME . '/login" target="_blank">vào đây</a></b> để đăng nhập tài khoản.';
        $data['step'][] = 'Sau khi đăng nhập mời bạn ghé thăm <b><a href="' . HOME . '" target="_blank">trang chủ</a></b> của mình.';
        $data['step'][] = 'Bây giờ hãy tiếp tục ghé thăm trang <b><a href="' . HOME . '" target="_blank">quản trị</a></b> nhé.';
        $data['manual'] = 'Còn chờ gì nữa mà không thử với những bài viết đầu tiên :D<br/>
                Để viết những bài đầu tiên bạn có thể vào <b><a href="' . HOME . '/admin/general/modulescp?appFollow=blog/manager" target="_blank">Trang quản lí blog</a></b> để viết bài.<br/>
                Hoặc bạn có thể truy cập theo đường dẫn sau: <span class="zen-path">Admin CP, Modules cpanel, Quản lí blog, Quản lí nội dung</span>
                Chắc bạn cũng đã hiểu phần nào cấu trúc website rồi chứ ^_^';
        $data['notice'] = 'Nếu có bất kì thắc mắc gì vui lòng truy cập website <a href="http://zencms.vn" target="_blank">http://zencms.vn</a> để nhận được hỗ trợ nhé';
        ZenView::set_title('Khám phá ZenCMS 5');
        $this->view->data = $data;
        $this->view->show('home');
    }
}
