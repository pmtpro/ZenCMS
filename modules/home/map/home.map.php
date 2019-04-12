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
ZenView::section('Khám phá', function() {
    ZenView::col(
        function() {
            ZenView::col_item(4, function() {
                ZenView::block(ZenView::$D['thank'], function() {
                    ZenView::padded(function() {
                        foreach(ZenView::$D['step'] as $i=>$item) {
                            $i = $i+1;
                            ZenView::row('<span class="step-title">Bước ' . $i . ':</span> <span class="step-content">' . $item . '</span>');
                        }
                    });
                });
            });
            ZenView::col_item(4, function() {
                ZenView::block('Hướng dẫn', function() {
                    ZenView::padded(ZenView::$D['manual']);
                });
            });
            ZenView::col_item(4, function() {
                ZenView::block('Chú ý', function() {
                    ZenView::padded(ZenView::$D['notice']);
                });
            });
        }
    );
});