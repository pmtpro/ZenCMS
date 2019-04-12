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

if (!function_exists('gadget_search_push')) {

    function gadget_search_push($formname = 'SearchPushUp')
    {
        return '<script type="text/javascript">
                <!--
                function submit' . $formname . '() {
                    if (this.key.value) this.action = "' . HOME . '/search-" + this.key.value + "" + this.action;
                }
                window.onload = function() {
                    document.' . $formname . '.onsubmit = submit' . $formname . ';
                }
                //-->
                </script>';
    }
}

if (!function_exists('gadget_ckeditor')) {
    function gadget_ckeditor($elementID = 'content', $options = array()) {
        global $registry;
        if (is_mobile()) return;
        ZenView::append_foot('<script type="text/javascript" src="' . _URL_FILES_SYSTEMS . '/js/ckeditor/ckeditor.js?ver=234"></script>');

        $gadget_list_sm = array();
        $user = $registry->user;
        if (!empty($user['id']) && is_array($user['smiles'])) {
            $list_smile = list_smile();
            foreach ($user['smiles'] as $sm) {
                $gadget_list_sm[] = array(
                    'full_url' => $list_smile[$sm]['full_url'],
                    'key' => $sm
                );
            }
        }

        if (empty($options['type'])) {
            $options['type'] == 'html';
        }

        if ($options['type'] == 'bbcode') {
            $find_bbcode = false;
            $hash_plugin = explode(',', $options['config']['extraPlugins']);
            foreach($hash_plugin as $plugin) {
                if (trim($plugin) == 'bbcode') {
                    $find_bbcode = true;
                    break;
                }
            }
            if ($find_bbcode == false) {
                $options['config']['extraPlugins'] = 'bbcode,' . $options['config']['extraPlugins'];
            }
        } elseif ($options['type'] == 'mini-bbcode') {
            $options['config']['extraPlugins'] = 'bbcode,image2';
            $options['config']['height'] = '70px';
            $options['config']['toolbar'] = array(
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Flash', 'Smiley')
                ),
                array(
                    'name' => 'styles',
                    'items' => array('Styles', 'Format', 'Font', 'FontSize', 'RemoveFormat')
                )
            );
        } elseif ($options['type'] == 'mini-html') {
            $options['config']['height'] = '70px';
            $options['config']['toolbar'] = array(
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Flash', 'Smiley')
                ),
                array(
                    'name' => 'styles',
                    'items' => array('Styles', 'Format', 'Font', 'FontSize', 'RemoveFormat')
                )
            );
        }

        if (!empty($gadget_list_sm)) {
            $options['config']['smiley_images'] = $gadget_list_sm;
        }

        /**
         * define ckeditor config: height
         */
        if (empty($options['config']['height'])) {
            $options['config']['height'] = "600px";
        }

        /**
         * define ckeditor config: removePlugins
         */
        if (empty($options['config']['removePlugins'])) {
            $options['config']['removePlugins'] = "image";
        }

        /**
         * define ckeditor config: extraPlugins
         */
        if (empty($options['config']['extraPlugins'])) {
            $options['config']['extraPlugins'] = "codemirror,lineutils,widget,dialog,image2,imagebrowser";
        }

        /**
        if (empty($options['config']['filebrowserImageUploadUrl'])) {
            $options['config']['filebrowserImageUploadUrl'] = HOME . "/api/ckeditor/upload?type=image&token=" . genRequestToken() . "&is-ajax-request";
        }
        if (empty($options['config']['filebrowserImageBrowseUrl'])) {
            $options['config']['filebrowserImageBrowseUrl'] = HOME . "/api/ckeditor/browser?type=image&token=" . genRequestToken() . "&is-ajax-request";
        }
        */
        $append_content_arr = arrayToJson($options['config']);

        $foot = "<script>CKEDITOR.replace('$elementID', $append_content_arr);</script>";
        ZenView::append_foot($foot);
    }
}

if (!function_exists('gadget_loadjs')) {

    function gadget_loadjs($name, $dir = _URL_FILES_JS) {
        $dir = trim($dir, '/');
        $name = trim($name, '/');
        return '<script language="javascript" type="text/javascript" src="' . $dir . '/' . $name . '"></script>';
    }
}

if (!function_exists('gadget_editarea')) {

    function gadget_editarea($language = 'php', $filed = 'content') {
        $out = '';
        if (is_mobile()) return $out;
        return '<script language="javascript" type="text/javascript" src="'._URL_FILES_SYSTEMS.'/js/editarea/edit_area_full.js"></script>
            <script language="javascript" type="text/javascript">
            editAreaLoader.init({
                id : "'.$filed.'"
                ,syntax: "'.$language.'"
                ,start_highlight: true
                ,min_height: 500
                ,font_size: "9"
                ,toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
			    ,syntax_selection_allow: "css,html,txt,info,js,php,python,vb,xml,xhtml,c,cpp,sql,basic,pas,brainfuck"
            });
            </script>';
    }
}
