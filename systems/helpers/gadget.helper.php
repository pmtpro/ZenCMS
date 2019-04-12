<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

if (!function_exists('gadget_search_push')) {

    function gadget_search_push($formname = 'SearchPushUp')
    {

        return '<script type="text/javascript">
                <!--
                function submit' . $formname . '() {
                    this.action = "' . _HOME . '/search-" + this.key.value + "" + this.action;
                }
                window.onload = function() {
                    document.' . $formname . '.onsubmit = submit' . $formname . ';
                }
                //-->
                </script>';
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

        if (is_mobile()) {

            return $out;
        }
        return '<script language="javascript" type="text/javascript" src="'._URL_FILES_JS.'/editarea/edit_area_full.js"></script>
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
/**
 *
 * @param string $type
 * @param boolean $disablade_toolbar
 * @return string
 */
if (!function_exists('gadget_TinymceEditer')) {

    function gadget_TinymceEditer($type = 'html', $disablade_toolbar = FALSE, $import_data = array())
    {
        global $registry;

        $out = '';

        if (is_mobile()) {

            return $out;
        }
        $out .= '<script type="text/javascript" src="' . _URL_FILES_JS . '/jquery/jquery-1.9.1.min.js"></script>
            <script type="text/javascript" src="' . _URL_FILES_JS . '/jquery/jquery-migrate-1.1.1.min.js"></script>
            <script type="text/javascript" src="' . _URL_FILES_JS . '/snippet/jquery.snippet.min.js"></script>
            <script type="text/javascript" src="' . _URL_FILES_JS . '/snippet/jquery.snippet.run.js"></script>
            <link rel="stylesheet" type="text/css" href="' . _URL_FILES_CSS . '/jquery.snippet.min.css">';

        if (isset($import_data['image_list'])) {

            $insert_to_js_image_list = 'image_list:  ' . array_to_json($import_data['image_list']) . ',';

        } else {
            $insert_to_js_image_list = '';
        }

        if (isset($import_data['link_list'])) {

            $insert_to_js_link_list = 'link_list:  ' . array_to_json($import_data['link_list']) . ',';

        } else {
            $insert_to_js_link_list = '';
        }

        if (!empty($registry->user['id'])) {

            $insert_to_js_smile_list = 'smile_list: ' . array_to_json($registry->user['smiles']) . ',';

        } else {
            $insert_to_js_smile_list = 'smile_list: [],';
        }

        $all_smile = unserialize(file_get_contents(__FILES_PATH . '/systems/cache/smiles.dat'));

        $out_sm = array();

        if (isset($registry->user['smiles'])) {

            foreach ($all_smile as $key => $val) {

                if (in_array($key, $registry->user['smiles'])) {

                    $out_sm[$key] = $val;
                }
            }

        }
        $insert_to_js_all_smile = 'all_smile: ' . array_to_json($out_sm) . ',';

        if ($type == 'html') {

            if ($disablade_toolbar == true) {

                $out = '<style> #mce_82 {display: none;}</style>';
            }

            return $out . '<script type="text/javascript" src="' . _URL_FILES_JS . '/tinymce/tinymce.min.js"></script>
                        <script type="text/javascript">
                        tinymce.init({
                            selector: "textarea#content",
                            theme: "modern",
                            plugins: [
                               "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                               "searchreplace wordcount visualblocks visualchars code fullscreen",
                               "insertdatetime nonbreaking save table contextmenu directionality",
                                "emoticons template textcolor autosave code quote textarea table snippet youtube"
                            ],
                            toolbar1: " bold italic underline strikethrough | forecolor backcolor | textarea quote snippet | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | styleselect | fontselect fontsizeselect removeformat | table | inserttable | link unlink image youtube | charmap emoticons fullscreen | undo redo | code preview",
                            menubar: "false",
                            image_advtab: true,
                            ' . $insert_to_js_image_list . '
                            ' . $insert_to_js_link_list . '
                            ' . $insert_to_js_all_smile . '
                            ' . $insert_to_js_smile_list . '
                            height: 300,
                            entity_encoding : "raw",
                            convert_fonts_to_spans : true,
                            convert_urls: false,
                            document_base_url : "' . _HOME . '",
                        });
                        </script>';

        } elseif ($type == 'bbcode') {

            if ($disablade_toolbar == true) {

                $out = '<style> #mce_53 {display: none;}</style>';
            }

            return $out . '<script type="text/javascript" src="' . _URL_FILES_JS . '/tinymce/tinymce.min.js"></script>
                        <script type="text/javascript">
                        tinyMCE.init({
                                selector: "textarea#content",
                                theme : "modern",
                                mode : "none",
                                plugins : ["bbcode", "code", "textcolor", "link", "image", "wordcount", "preview", "emoticons", "insert_code", "quote", "textarea", "charmap", "fullscreen", "youtube"],
                                toolbar1: "bold italic underline strikethrough | forecolor removeformat | textarea quote insert_code | alignleft aligncenter alignright | bullist numlist | styleselect | link unlink image youtube | emoticons charmap fullscreen | undo redo | code preview",
                                menubar: "false",
                                height: 300,
                                ' . $insert_to_js_smile_list . '
                                ' . $insert_to_js_all_smile . '
                                entity_encoding : "raw",
                                add_unload_trigger : false,
                                remove_linebreaks : false,
                                inline_styles : false,
                                convert_fonts_to_spans : true,
                                forced_root_block : false,
                                convert_urls: false,
                                document_base_url : "' . _HOME . '",
                        });
                        </script>';

        } elseif ($type == 'bbcode_mini') {

            if ($disablade_toolbar == true) {

                $out .= '<style> #mce_39 {display: none;}</style>';
            }

            return $out . '<script type="text/javascript" src="' . _URL_FILES_JS . '/tinymce/tinymce.min.js"></script>
                        <script type="text/javascript">
                        tinyMCE.init({
                                selector: "textarea#content",
                                theme : "modern",
                                mode : "none",
                                plugins : ["bbcode", "code", "textcolor", "link", "image", "emoticons", "insert_code", "quote", "textarea", "snippet", "youtube"],
                                toolbar1: "bold italic underline | forecolor | textarea quote insert_code | aligncenter alignright  | bullist numlist | link unlink image youtube emoticons",
                                menubar: "false",
                                ' . $insert_to_js_smile_list . '
                                ' . $insert_to_js_all_smile . '
                                entity_encoding : "raw",
                                add_unload_trigger : false,
                                remove_linebreaks : false,
                                inline_styles : false,
                                convert_fonts_to_spans : true,
                                forced_root_block : false,
                                convert_urls: false,
                                document_base_url : "' . _HOME . '",
                        });
                        </script>';
        }
    }

}

?>
