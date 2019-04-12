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
defined('__ZEN_KEY_ACCESS') or die('No direct script access allowed');

Class blogControlsController Extends ZenController
{
    function forEditor() {
        $user = $this->user;
        $blogModel = $this->model->get('blog');
        $hook = $this->hook->get('blogControls');
        $obj = $this;

        /**
         * auto gen description
         */
        if (modConfig('turn_on_auto_gen_desc', 'blogControls') && !is_ajax_request()) {
            /**
             * run hook blog_data_before_to_database
             */
            run_hook('blog', 'blog_data_before_to_database', function ($data) use ($obj) {
                if (isset($data['content']) && empty($data['des'])) {
                    $num = modConfig('num_word_desc_auto_cut', 'blogControls');
                    if (empty($num)) $num = 160;
                    $content = h_decode($data['content']);
                    $hash = strip_tags($content);
                    if ($data['type_data'] == 'bbcode') {
                        $hash = preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $hash);
                    }
                    $hash = preg_replace('/(?:\s\s+|\n|\t)/is', ' ', trim($hash));

                    $hash = html_entity_decode($hash);
                    //$hash = preg_replace('/[[:blank:]]+/', ' ', $hash);

                    //$des = trim(mb_substr(trim($hash), 0, $num-1, 'UTF-8'));
                    //$data['des'] = $des;
                    $data['des'] = $obj->split_char($hash, $num, 1);
                }
                return $data;
            });
        }


        if (modConfig('turn_on_watermark', 'blogControls')) {
            /**
             * run hook watermark_image
             */
            run_hook('blogControls', 'watermark_image', function($image) use ($obj) {
                $text = modConfig('text_watermark', 'blogControls');
                if (!$text) return $image;
                else return $obj->watermark_image($image, $text);
            });
            /**
             * run hook ckeditor_data_after_upload
             */
            run_hook('api', 'ckeditor_data_after_upload', function($dataUp) use ($obj) {
                $text = modConfig('text_watermark', 'blogControls');
                if (!$text) return $dataUp;
                $dataUp['full_path'] = $obj->watermark_image($dataUp['full_path'], $text);
                return $dataUp;
            });
            /**
             * run hook image_data_after_upload
             */
            run_hook('blog', 'image_data_after_upload', function ($data) use ($hook) {
                $data['full_path'] = $hook->loader('watermark_image', $data['full_path']);
                return $data;
            });
        }

        /**
         * auto import and watermark image
         */
        if (modConfig('turn_on_import', 'blogControls') && (!is_ajax_request() || ZenInput::get('CKEditorFuncNum'))) {

            if (modConfig('import_local', 'blogControls')) {

                /**
                 * run hook import_image
                 */
                run_hook('blogControls', 'import_image', function ($image, $data) use ($hook, $blogModel, $user) {
                    $security = load_library('security');
                    /**
                     * set directory upload
                     */
                    $dir = __FILES_PATH . '/posts/images';
                    $subDir = autoMkSubDir($dir);
                    $upload = load_library('upload', array('init_data' => $image));
                    if ($upload->uploaded) {
                        $upload->file_new_name_body = $data['name'];
                        $upload->allowed = array('image/*');
                        $upload->process($dir . '/' . $subDir);
                        if ($upload->processed) {
                            $dataImport = $upload->data();
                            /**
                             * watermark_image hook *
                             */
                            $dataImport['full_path'] = $hook->loader('watermark_image', $dataImport['full_path']);

                            $url = $subDir . '/' . $dataImport['file_name'];
                            $out = 'files/posts/images/' . $url;
                            $insert_img['uid'] = $user['id'];
                            $insert_img['sid'] = $data['id'] ? $data['id'] : (int)$security->removeSQLI(ZenInput::get('id'));
                            $insert_img['url'] = $url;
                            $insert_img['type'] = 'content';
                            $insert_img['time'] = time();
                            /**
                             * data_post_image_before_to_database hook*
                             */
                            $insert_img = $hook->loader('data_post_image_before_to_database', $insert_img);
                            $blogModel->insert_image($insert_img);
                        } else return $image;
                        $upload->clean();
                    } else return $image;
                    return $out;
                });
            }

            /**
             * run hook blog_data_before_to_database
             */
            run_hook('blog', 'blog_data_before_to_database', function ($data) use ($hook) {
                $parse = load_library('parse');
                $list_image = $parse->image_url($data['content']);
                $replace = array();
                $static_data['id'] = $data['id'];
                $static_data['name'] = $data['name'];
                $static_data['url'] = $data['url'];
                foreach ($list_image as $img) {
                    $beforeImg = $img;
                    $img = $hook->loader('import_image', $img, array('var' => $static_data));
                    if ($img && $img != $beforeImg) {
                        $replace[$beforeImg] = $img;
                    }
                }
                /**
                 * safe content
                 */
                $data_content = $data['content'];
                if ($replace) $data['content'] = strtr($data['content'], $replace);
                if (empty($data['content'])) {
                    $data['content'] = $data_content;
                }
                unset($import_image, $replace, $data_content);
                return $data;
            });
        }
    }

    public function watermark_image($url, $text)
    {
        $size = getimagesize($url); //get image size
        $w = $size[0];
        $h = $size[1];
        $type = $size[2];
        switch ($type) {
            case '2':
                $img = imagecreatefromjpeg($url);
                break;
            case '1':
                $img = imagecreatefromgif($url);
                break;
            case '3':
                $img = imagecreatefrompng($url);
                break;
            case '6':
                $img = imagecreatefromwbmp($url);
                break;
            default:
                $img = imagecreatefromjpeg($url);
                break;
        }
        $new_h = $h + 25;
        $source = @imagecreatetruecolor($w, $new_h); //create background image
        $color = imagecolorallocate($source, 238, 238, 238);
        imagefill($source, 0, 0, $color); //fill white background
        $text_color = imagecolorallocate($source, 194, 194, 194); //set text color
        imagettftext($source, 15, 0, 20, $h + 20, $text_color, __MODULES_PATH . '/blogControls/files/fonts/BRLNSR.TTF', $text); //watermark
        imagecopy($source, $img, 0, 0, 0, 0, $w, $h);
        switch ($type) {
            case '2':
                @imagejpeg($source, $url);
                break;
            case '1':
                imagegif($source, $url);
                break;
            case '3':
                imagepng($source, $url);
                break;
            case '6':
                imagewbmp($source, $url);
                break;
            default:
                imagejpeg($source, $url);
                break;
        }
        return $url;
    }

    public function split_char($str, $limit = 50, $opt = 0) {
        $string = mb_substr($str,0, $limit, 'UTF-8');
        if($opt == 1) {
            $tempLen = mb_strlen($string);
            for($i = $limit; $i < $tempLen; $i++) {
                $str_tmp = mb_substr($str, $i,1, 'UTF-8');
                if($str_tmp != ' ') {
                    $string .= $str_tmp;
                }
                else { break;}
            }
        }
        elseif($opt == -1) {
            for($i = $limit; $i > 2; $i--) {
                $str_tmp = mb_substr($str, $i, 1, 'UTF-8');
                if($str_tmp != ' ') {
                    $string = mb_substr($string,0, -1, 'UTF-8');
                }
                else {break;}
            }
        }
        return $string;
    }

    public function settings($app = array('index'))
    {
        load_apps('blogControls/apps/settings', $app);
    }
}