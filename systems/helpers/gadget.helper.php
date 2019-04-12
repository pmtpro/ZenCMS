<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
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
    /**
     * @param string $elementID
     * @param array $options
     */
    function gadget_ckeditor($elementID = 'content', $options = array()) {
        global $registry;
        if (is_mobile()) return;
        ZenView::append_foot('<script type="text/javascript" src="' . _URL_FILES_SYSTEMS . '/js/ckeditor/ckeditor.js?ver=234"></script>');

        if (empty($options['config']['extraPlugins'])) $options['config']['extraPlugins'] = array('image2');
        if (!in_array('image2', $options['config']['extraPlugins'])) {
            $options['config']['extraPlugins'][] = 'image2';
        }
        /**
         * define ckeditor config: removePlugins
         */
        if (empty($options['config']['removePlugins'])) $options['config']['removePlugins'] = array('image');
        if (!in_array('image', $options['config']['removePlugins'])) $options['config']['removePlugins'][] = 'image';

        if (empty($options['type'])) {
            $options['type'] = 'html';
        }
        if (empty($options['config']['baseHref'])) {
            $options['config']['baseHref'] = HOME . '/';
        }

        if ($options['type'] == 'bbcode') {
            if (!in_array('bbcode', $options['config']['extraPlugins'])) {
                $options['config']['extraPlugins'][] = 'bbcode';
            }
            if (!in_array('toolbarToggle', $options['config']['extraPlugins'])) {
                $options['config']['extraPlugins'][] = 'toolbarToggle';
            }
            if (!isset($options['config']['toolbar'])) $options['config']['toolbar'] = array(
                array(
                    'name'  => 'document',
                    'items' => array('ToolbarToggle', 'Source')
                ),
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Smiley')
                ),
                array(
                    'name' => 'styles',
                    'items' => array('FontSize')
                )
            );
        } elseif ($options['type'] == 'mini-bbcode') {
            if (!in_array('bbcode', $options['config']['extraPlugins'])) {
                $options['config']['extraPlugins'][] = 'bbcode';
            }
            if (!isset($options['config']['height'])) $options['config']['height'] = '70px';
            if (!isset($options['config']['toolbar'])) $options['config']['toolbar'] = array(
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Smiley')
                ),
                array(
                    'name' => 'styles',
                    'items' => array('FontSize')
                )
            );
        } elseif ($options['type'] == 'mini-html') {
            if (!isset($options['config']['height'])) $options['config']['height'] = '70px';
            if (!isset($options['config']['toolbar'])) $options['config']['toolbar'] = array(
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Smiley')
                ),
                array(
                    'name' => 'styles',
                    'items' => array('Styles', 'Format', 'Font', 'FontSize')
                )
            );
        } else {
            if (!in_array('toolbarToggle', $options['config']['extraPlugins'])) {
                $options['config']['extraPlugins'][] = 'toolbarToggle';
            }
            if (!isset($options['config']['toolbar'])) $options['config']['toolbar'] = array(
                array(
                    'name'  => 'document',
                    'items' => array('ToolbarToggle', 'Maximize', 'Source')
                ),
                array(
                    'name' => 'basicstyles',
                    'items' => array('Bold', 'Italic', 'Underline', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat')
                ),
                array(
                    'name' => 'links',
                    'items' => array('Link', 'Unlink', '-', 'Image', 'Youtube', 'Smiley')
                ),
               array(
                   'name'   => 'paragraph',
                   'items'  => array('NumberedList', 'BulletedList', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock')
               ),
                array(
                    'name' => 'styles',
                    'items' => array('Format', 'Font', 'FontSize')
                )
            );
        }

        /**
         * add smiley plugin
         */
        if (in_array('smiley', $options['config']['extraPlugins'])) {
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
            if ($gadget_list_sm) $options['config']['smiley_images'] = $gadget_list_sm;
        }

        /**
         * gen ckeditor config: height
         */
        if (empty($options['config']['height'])) {
            $options['config']['height'] = "600px";
        }

        /**
         * gen ckeditor config: extraPlugins
         */
        $needed = array('codemirror', 'lineutils', 'widget', 'dialog', 'image2', 'youtube');
        foreach ($needed as $i) {
            if (!in_array($i, $options['config']['extraPlugins'])) {
                $options['config']['extraPlugins'][] = $i;
            }
        }
        $options['config']['extraPlugins'] = implode(',', $options['config']['extraPlugins']);

        /**
         * gen ckeditor config: removePlugins
         */
        $options['config']['removePlugins'] = implode(',', $options['config']['removePlugins']);

        /**
         * convert array to json
         */
        $append_content_arr = arrayToJson($options['config']);

        $foot = "<script>CKEDITOR.replace('$elementID', $append_content_arr);</script>";
        ZenView::append_foot($foot);
    }
}

if (!function_exists('gadget_editarea')) {

    function gadget_editarea($filed = 'content', $language = 'php') {
        $out = '';
        if (is_mobile()) return $out;
        if (in_array($language, array('php5', 'php4'))) {
            $language = 'php';
        } elseif (in_array($language, array('css', 'css3'))) {
            $language = 'css';
        } elseif (in_array($language, array('html', 'html5', 'htm'))) {
            $language = 'html';
        }
        $editarea_ext_allow = array('css', 'html', 'txt', 'js', 'php', 'python', 'vb', 'xml', 'xhtml', 'c', 'cpp', 'sql', 'basic', 'pas', 'brainfuck');
        if (!in_array($language, $editarea_ext_allow)) {
            $language = '';
        }
        ZenView::append_head('<script language="javascript" type="text/javascript" src="' . _URL_FILES_SYSTEMS . '/js/editarea/edit_area_full.js"></script>');
        ZenView::append_head('<link href="' . _URL_FILES_SYSTEMS . '/styles/editarea/style.css" rel="stylesheet" type="text/css">');
        ZenView::append_foot('<script language="javascript" type="text/javascript">
            editAreaLoader.init({
                id : "' . $filed . '",
                ' . ($language? 'syntax: "' . $language . '",' : '') . '
                start_highlight: true,
                min_height: 400,
                font_size: "10",
                toolbar: "search, go_to_line, undo, redo, change_smooth_selection, highlight, reset_highlight, select_font, syntax_selection",
                syntax_selection_allow: "css,html,txt,js,php,python,vb,xml,xhtml,c,cpp,sql,basic,pas,brainfuck",
                word_wrap: false
            });
            </script>');
    }
}

if (!function_exists('gadget_codemirror')) {
    function gadget_codemirror($textarea_id, $mode) {
        $define_mode = array(
            'js' => 'javascript',
            'html' => 'htmlmixed'
        );
        $define_mime = array(
            'php' => 'application/x-httpd-php'
        );
        if (isset($define_mode[$mode])) {
            $mode = $define_mode[$mode];
        }
        if (isset($define_mime[$mode])) {
            $config['mode'] = '"' . $define_mime[$mode] . '"';
        } else {
            $config['mode'] = '"' . $mode . '"';
        }
        ZenView::append_head('<script src="' . _URL_FILES_SYSTEMS . '/js/codemirror/lib/codemirror.js"></script>
        <link rel="stylesheet" href="' . _URL_FILES_SYSTEMS . '/js/codemirror/lib/codemirror.css">
        <script src="' . _URL_FILES_SYSTEMS . '/js/codemirror/mode/' . $mode . '/' . $mode . '.js"></script>


        <script src="' . _URL_FILES_SYSTEMS . '/js/codemirror/mode/clike/clike.js"></script>
        ');
        ZenView::append_foot('<script>
        var editor = CodeMirror.fromTextArea(document.getElementById("' . $textarea_id . '"), {
        lineNumbers: true,
        extraKeys: {"Ctrl-Space": "autocomplete"},
        mode: ' . $config['mode'] . '
      });
    </script>');
    }
}
