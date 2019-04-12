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
ZenView::section('Welcome to ZenCMS 5', function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();
    ZenView::col(function() {
        ZenView::col_item(6, function() {
            ZenView::block('Mục tiêu của ZenCMS 5', function() {
                echo '<div class="box-section news with-icons">
                  <div class="avatar green"><i class=" icon-quote-left icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title">Đối với webmaster</div>
                    <div class="news-text">
                      ZenCMS mong muốn đem đến một sản phẩm hoàn hảo với cách sử dụng đơn giản mà vẫn có khả năng tùy biến cao theo nhu cầu người dùng. Không những thế, ZenCMS mong muốn trở thành cầu nối giữa Publisher và các nhà phần phối nhằm đem lại lợi ích tốt nhất cho 2 đối tượng này.
                    </div>
                  </div>
                </div>';
                echo '<div class="box-section news with-icons">
                  <div class="avatar green"><i class=" icon-quote-left icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title">Đối với DEV</div>
                    <div class="news-text">
                      ZenCMS đang nỗ lực đem lại 1 sản phẩm hoàn hảo, linh hoạt nhưng vẫn đảm bảo khả năng thiết kế ứng dụng đơn giản phù hợp với mọi ứng dụng mà DEV mong muốn.
                    </div>
                  </div>
                </div>';
            });
        });
        ZenView::col_item(6, function() {
            ZenView::block('Hỗ trợ', function() {
                echo '<div class="box-section news with-icons">
                  <div class="avatar blue"><i class="icon-home icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title"><a href="http://zencms.vn" target="_blank">ZenCMS.VN</a></div>
                    <div class="news-text">
                      Trang chủ của ZenCMS
                    </div>
                  </div>
                </div>';
                echo '<div class="box-section news with-icons">
                  <div class="avatar blue"><i class="icon-legal icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title"><a href="http://zencms.vn/license" target="_blank">License</a></div>
                    <div class="news-text">
                      Điều khoản sử dụng ZenCMS
                    </div>
                  </div>
                </div>';
                echo '<div class="box-section news with-icons">
                  <div class="avatar blue"><i class="icon-question-sign icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title"><a href="http://zencms.vn/faq-cau-hoi-thuong-gap-18.html" target="_blank">FAQ</a></div>
                    <div class="news-text">
                      Những câu hỏi thưởng gặp khi sử dụng ZenCMS
                    </div>
                  </div>
                </div>';
                echo '<div class="box-section news with-icons">
                  <div class="avatar purple"><i class="icon-user icon-2x"></i></div>
                  <div class="news-content">
                    <div class="news-title"><a href="http://zenthang.com" target="_blank">Zen Thắng</a></div>
                    <div class="news-text">
                      Trang tác giả phần mềm
                    </div>
                  </div>
                </div>';
            });
        });
    });
});