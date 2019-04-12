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

class ImageEditor
{
    public $src, $image = false, $wdith, $height, $dstInfo, $type;
    var $image_type;
    var $path = '';
    var $sql_path = '';
    var $image_name;
    var $w_resize;
    var $h_resize;
    var $string;
    var $fontsize;
    var $font;
    var $rotation;
    var $color;
    var $background;
    var $opacity;

    var $watermark_obj;
    var $src_obj;

    public $error = array();

    public function set_error($msg)
    {
        $this->error[] = $msg;
    }

    function load($file = '')
    {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            $this->set_error("GD library is disable");
            return false;
        }
        if (file_exists($file)) {
            $this->path = $file;
        }
        $this->src = $file;
        $image_info = @getimagesize($file);
        $this->width = $image_info[0];
        $this->height = $image_info[1];
        $this->image_type = $image_info[2];

        if (!$this->width && $this->height) {
            $this->set_error("Can't load image info");
        }

        switch ($this->image_type) {
            case IMAGETYPE_JPEG:
                $this->image = @imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_GIF:
                $this->image = @imagecreatefromgif($file);
                break;
            case IMAGETYPE_PNG:
                $img = @imagecreatefrompng($file);
                imagealphablending($img, true); // setting alpha blending on
                imagesavealpha($img, true); // save alphablending setting (im
                $this->image = $img;
                break;
            case IMAGETYPE_BMP:
                $this->image = @imagecreatefromwbmp($file);
                break;
            default:
                $this->image = @imagecreatefromjpeg($file);
                break;
        }
        if (empty($this->image)) {
            $this->set_error("Can't create image");
            return FALSE;
        }

        return TRUE;
    }

    function save($file = '', $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
    {
        if (empty($file)) {
            $file = $this->path;
        }
        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $ok = @imagejpeg($this->image, $file, $compression);
                if ($ok)
                    return true;
                break;
            case IMAGETYPE_GIF:
                $ok = @imagegif($this->image, $file, $compression);
                if ($ok)
                    return true;
                break;
            case IMAGETYPE_PNG:
                $ok = @imagepng($this->image, $file, $compression);
                if ($ok)
                    return true;
                break;
            case IMAGETYPE_BMP:
                $ok = @imagewbmp($this->image, $file, $compression);
                if ($ok)
                    return true;
                break;
            default:
                $ok = @imagejpeg($this->image, $file, $compression);
                if ($ok)
                    return true;
                else {
                    $this->set_error('Do not support this image');
                }
                break;
        }
        if ($permissions != null) {
            changeMod($file, $permissions);
        }
        $this->set_error("Can't create image from $file");
        return false;
    }

    function getWidth()
    {

        return @imagesx($this->image);
    }

    function getHeight()
    {

        return @imagesy($this->image);
    }

    function resizeToHeight($height)
    {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($maxWidth = 0, $maxHeight = 0, $holdRatio = FALSE)
    {
        if ($maxWidth <= 0 && $maxHeight <= 0) {
            $this->set_error("The image size is incorrect format");
            return false;
        }
        if ($holdRatio) {
            $srcRatio = $this->width / $this->height;
            $destRatio = $maxHeight ? $maxWidth / $maxHeight : 0;
            if ($destRatio > $srcRatio || $maxHeight == 0) {
                $dstWidth = $maxWidth;
                $dstHeight = round($maxWidth / $srcRatio);
            } else {
                $dstWidth = round($maxHeight * $srcRatio);
                $dstHeight = $maxHeight;
            }
        } else {
            $dstHeight = $maxHeight;
            $dstWidth = $maxWidth;
        }

        $tmp = imagecreatetruecolor($dstWidth, $dstHeight);
        imagealphablending($tmp, false);
        imagefill($tmp, 0, 0, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
        imagesavealpha($tmp, true);

        if (imagecopyresampled($tmp, $this->image, 0, 0, 0, 0, $dstWidth, $dstHeight, $this->width, $this->height) === true) {
            $this->width = $dstWidth;
            $this->height = $dstHeight;
            @imagedestroy($this->image);

            $this->image = imagecreatetruecolor($dstWidth, $dstHeight);
            imagealphablending($this->image, false);
            imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, 0, 0, 0, 127));
            imagesavealpha($this->image, true);
            imagecopy($this->image, $tmp, 0, 0, 0, 0, $dstWidth, $dstHeight);
            @imagedestroy($tmp);
            return true;
        }
        $this->set_error("Can not copy images");
        return false;
    }

    public function addtext($text, $font, $size = 10, $angle = 0, $x = 0, $y = 0, $c = array(0, 0, 0) /* array(r,g,b) */)
    {
        $bbox = imageftbbox($size, $angle, $font, $text);
        return @imagefttext($this->image, $size, $angle, (($x >= 0) ? ($x - $bbox[6]) : ($x + $this->width + $bbox[6] - $bbox[4])), (($y >= 0) ? ($y - $bbox[7]) : ($y + $this->height + $bbox[7] - $bbox[5] - 5)), imagecolorallocate($this->image, $c[0], $c[1], $c[2]), $font, $text);
    }

    public function watermark($wm, $x = 0, $y = 0, $maxWidth = 0, $maxHeight = 0, $holdRatio = true)
    {
        $img_wm = $wm;
        $wm = new ImageEditor;
        $wm->load($img_wm);

        if (!is_resource($wm->image))
            return false;
        if ($maxWidth > 0 || $maxHeight > 0)
            $wm->resize($maxWidth, $maxHeight, $holdRatio);
        $x = (int)($x >= 0) ? $x : ($this->width + $x - $wm->width);
        $y = (int)($y >= 0) ? $x : ($this->height + $y - $wm->height);

        imagelayereffect($this->image, IMG_EFFECT_ALPHABLEND);

        return imagecopy($this->image, $wm->image, $x, $y, 0, 0, $wm->width, $wm->height);
    }

    public function effect($effect)
    {
        switch (strtoupper($effect)) {
            case "BLUR":
                $matrix = array(array(1 / 9, 1 / 9, 1 / 9), array(1 / 9, 1 / 9, 1 / 9), array(1 / 9, 1 / 9, 1 / 9));
                break;
            case "EDGE":
                $matrix = array(array(0, -1, 0), array(-1, 4, -1), array(0, -1, 0));
                break;
            case "SHARPENA":
                $matrix = array(array(0, -1, 0), array(-1, 5, -1), array(0, -1, 0));
                break;
            case "SHARPENB":
                $matrix = array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1));
                break;
            case "EMBOSS":
                $matrix = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
                break;
            case "LIGHT":
                $matrix = array(array(0, 0, 1), array(0, 1, 0), array(1, 0, 0));
                break;
            default:
                return false;
                break;
        }
        return @imageconvolution($this->image, $matrix, 1, 0);
    }

    function text_watermark($data = array('string' => 'ZenThang',
        'font' => 'comic',
        'fontsize' => 10,
        'rotation' => 0,
        'color' => '#000000',
        'background' => '#ffffff',
        'opacity' => 50,
        'quality' => 90))
    {

        if (empty($data)) {
            $data = array('string' => 'ZenThang',
                'font' => 'comic',
                'fontsize' => 10,
                'rotation' => 0,
                'color' => '#000000',
                'background' => '#ffffff',
                'opacity' => 50,
                'quality' => 90);
        }

        if (!isset($data['string'])) {
            $data['string'] = 'ZenThang';
        }
        if (!isset($data['font'])) {
            $data['font'] = 'comic';
        }
        if (!isset($data['fontsize'])) {
            $data['fontsize'] = 10;
        }
        if (!isset($data['rotation'])) {
            $data['rotation'] = 0;
        }
        if (!isset($data['color'])) {
            $data['color'] = '#000000';
        }
        if (!isset($data['background'])) {
            $data['background'] = '#ffffff';
        }
        if (!isset($data['opacity'])) {
            $data['opacity'] = 50;
        }
        if (!isset($data['quality'])) {
            $data['quality'] = 90;
        }

        if (!isset($data['quality'])) {
            $watermark_quality = 90; // image quality
        } else {
            $watermark_quality = $data['quality'];
        }

        $this->string = $data['string'];
        $this->fontsize = $data['fontsize']; //px
        $this->font = __SITE_PATH . '/files/systems/fonts/' . $data['font'];
        $this->rotation = $data['rotation'];
        $this->color = $this->hex2rgb($data['color']);
        $this->background = $this->hex2rgb($data['background']);
        $this->opacity = $data['opacity'];

        if (!defined('WATERMARK_OVERLAY_OPACITY')) {
            define('WATERMARK_OVERLAY_OPACITY', $this->opacity);
        }
        if (!defined('WATERMARK_OUTPUT_QUALITY')) {
            define('WATERMARK_OUTPUT_QUALITY', $watermark_quality);
        }
    }

    function text_watermark_create($source_file_path, $output_file_path = '')
    {

        /*
          if (empty($this->image)) {
          return FALSE;
          }

          if (empty($output_file_path)) {
          $output_file_path = $this->path;
          }

          if (empty($output_file_path)) {
          return FALSE;
          }

          $source_width = @imagesx($this->image);
          $source_height = @imagesy($this->image);

          if (!$source_width || !$source_height) {
          return FALSE;
          }
          $source_gd_image = $this->image;
         */
        global $width_t, $height_t, $source_height_new, $source_height, $sym_dir;
        list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
        if ($source_type === NULL) {
            return false;
        }

        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_file_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_file_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_file_path);
                break;
            default:
                return false;
        }


        $string = $this->string;
        $fontsize = $this->fontsize;
        $font = $this->font;
        $rotation = $this->rotation;
        $color = $this->color;
        $background = $this->background;

        $bbox = imageftbbox($fontsize, $rotation, $font, $string);
        $width_t = abs($bbox[0]) + abs($bbox[2]); //chieu rong chu
        $height_t = abs($bbox[1]) + abs($bbox[5]); // chieu cao chu
        $source_height_new = $height_t + 10;

        while ($width_t < $source_width) {
            $fontsize = $fontsize + 1;
            $bbox = imageftbbox($fontsize, $rotation, $font, $string);
            $width_t = abs($bbox[0]) + abs($bbox[2]); //chieu rong chu
            $height_t = abs($bbox[1]) + abs($bbox[5]); // chieu cao chu
            $source_height_new = $height_t + $height_t / 2;
            if ($source_height_new >= $source_height / 15)
                break;
        }

        while ($width_t > $source_width) {
            $fontsize = $fontsize - 1;
            $bbox = imageftbbox($fontsize, $rotation, $font, $string);
            $width_t = abs($bbox[0]) + abs($bbox[2]); //chieu rong chu
            $height_t = abs($bbox[1]) + abs($bbox[5]); // chieu cao chu
            $source_height_new = $height_t + $height_t / 2;
        }

        $image = ImageCreate($source_width, $source_height_new);
        $white = ImageColorAllocate($image, 255, 255, 255);
        $black = ImageColorAllocate($image, 0, 0, 0);
        $bg = ImageColorAllocate($image, $background[0], $background[1], $background[2]);
        ImageFill($image, 0, 0, $bg);

        if (imagecolorstotal($image) >= 255) {
            //palette used up; pick closest assigned color
            $get_color = imagecolorclosest($image, $color[0], $color[1], $color[2]);
        } else {
            //palette NOT used up; assign new color
            $get_color = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        }
        $vtx = 3;
        $vty = $source_height_new - $source_height_new / 3;
        ImagettfText($image, $fontsize, $rotation, $vtx, $vty, $get_color, $font, $string);

        $overlay_gd_image = $image;
        $overlay_width = $source_width;
        $overlay_height = $source_height_new;

        imagecopymerge(
            $source_gd_image, $overlay_gd_image, $source_width - $overlay_width, $source_height - $overlay_height, 0, 0, $overlay_width, $overlay_height, WATERMARK_OVERLAY_OPACITY
        );

        imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);

        imagedestroy($source_gd_image);
        imagedestroy($overlay_gd_image);
        return $output_file_path;
    }


    public function output($type, $dst = NULL, $quality = 100)
    {
        $type = strtoupper($type);

        if ($dst != NULL) {
            switch ($type) {
                case "JPG":
                case "JPEG":
                    return @imageJpeg($this->image, $dst, $quality);
                case "PNG":
                    return @imagePng($this->image, $dst);
                case "GIF":
                    return @imageGif($this->image, $dst);
                default:
                    return false;
            }
        } else {
            switch ($type) {
                case "JPG":
                case "JPEG":
                    header('Content-type: image/jpeg');
                    return @imageJpeg($this->image, NULL, $quality);
                case "PNG":
                    header('Content-Type: image/png');
                    return @imagePng($this->image);
                case "GIF":
                    header('Content-type: image/gif');
                    return @imageGif($this->image);
                default:
                    return false;
            }
        }
        @imagedestroy($this->image);
        //return $ok;
    }

    function hex2rgb($hex = '')
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

}

?>