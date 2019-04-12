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

Class watermark
{

    public $error = array();
    public $in_path_src;
    public $out_path_src;
    public $in_path_wm;
    public $out_path_wm;

    public $load_path;
    public $load_width;
    public $load_height;
    public $load_type;
    public $load_image;

    public $src_path;
    public $src_image;
    public $src_width;
    public $src_height;
    public $src_type;

    public $wm_path;
    public $wm_image;
    public $wm_width;
    public $wm_height;
    public $wm_type;
    public $alpha_level = 100;
    public $complete_image;

    public function set_error($msg)
    {
        $this->error[] = $msg;
    }

    /**
     * load image need watermark
     *
     * @param string $file
     * @return boolean
     */
    public function load_src($file = false)
    {
        if (empty($file)) {
            return false;
        }
        $this->load_path = $file;
        $this->in_path_src = $file;

        $out = $this->load($file);
        if (!$out) {
            return false;
        }
        $this->src_path = $this->load_path;
        $this->src_image = $out['image'];
        $this->src_width = $out['width'];
        $this->src_height = $out['height'];
        $this->src_type = $out['type'];
    }

    /**
     * load image use watermark
     *
     * @param string $file
     * @return boolean
     */
    public function load_wm($file = false)
    {

        if (empty($file)) {
            return false;
        }
        $this->load_path = $file;
        $this->in_path_wm = $file;

        $out = $this->load($file);
        if (!$out) {
            return false;
        }
        $this->wm_path = $this->load_path;
        $this->wm_image = $out['image'];
        $this->wm_width = $out['width'];
        $this->wm_height = $out['height'];
        $this->wm_type = $out['type'];
    }

    /**
     * load image, return image resource
     * @return boolean
     */
    function load($file)
    {

        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            $this->set_error("GD library is disable");
            return false;
        }

        if (empty($file)) {
            return false;
        }

        $image_info = @getimagesize($file);
        $out['width'] = $image_info[0];
        $out['height'] = $image_info[1];
        $out['type'] = $image_info[2];

        if (!$out['width'] || !$out['height']) {
            $this->set_error("Can't load image info");
        }

        switch ($out['type']) {
            case IMAGETYPE_JPEG:
                $out['image'] = @imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_GIF:
                $out['image'] = @imagecreatefromgif($file);
                break;
            case IMAGETYPE_PNG:
                $img = @imagecreatefrompng($file);
                imagealphablending($img, true); // setting alpha blending on
                imagesavealpha($img, true); // save alphablending setting (im
                $out['image'] = $img;
                break;
            case IMAGETYPE_BMP:
                $out['image'] = @imagecreatefromwbmp($file);
                break;
            default:
                $out['image'] = @imagecreatefromjpeg($file);
                break;
        }
        if (empty($out['image'])) {
            $this->set_error("Can't create image");
            return FALSE;
        }
        return $out;
    }

    /**
     * save new image as file path
     *
     * @param string $file
     * @param int $compression
     * @param int $permissions
     * @return boolean
     */
    function save($file = '', $compression = 75, $permissions = null)
    {
        if (!is_resource($this->complete_image)) {
            return false;
        }
        if (empty($file)) {
            $file = $this->src_path;
        }
        switch ($this->src_type) {
            case IMAGETYPE_JPEG:
                $ok = @imagejpeg($this->complete_image, $file, $compression);
            case IMAGETYPE_GIF:
                $ok = @imagegif($this->complete_image, $file, $compression);
            case IMAGETYPE_PNG:
                $ok = @imagepng($this->complete_image, $file, $compression);
            case IMAGETYPE_BMP:
                $ok = @imagewbmp($this->complete_image, $file, $compression);
            default:
                $ok = @imagejpeg($this->complete_image, $file, $compression);
                break;
        }
        if ($permissions != null) {
            changemod($file, $permissions);
        }

        if ($ok) {
            return true;
        }

        $this->set_error("Can't create image from $file");
        return false;
    }

    /**
     * watermark process
     * @return boolean
     */
    function do_watermark()
    {

        $src_image_obj = $this->src_image;
        $wm_image_obj = $this->wm_image;
        $alpha_level = $this->alpha_level;


        if (!is_resource($src_image_obj)) {
            $this->set_error("Can't load resource image");
            return false;
        }
        if (!is_resource($wm_image_obj)) {
            $this->set_error("Can't load watermark image");
            return $src_image_obj;
        }

        $alpha_level /= 100; # convert 0-100 (%) alpha to decimal
        # calculate our images dimensions
        $src_image_obj_w = imagesx($src_image_obj);
        $src_image_obj_h = imagesy($src_image_obj);
        $wm_image_obj_w =  imagesx($wm_image_obj);
        $wm_image_obj_h =  imagesy($wm_image_obj);

        # determine center position coordinates
        $src_image_obj_min_x = floor(($src_image_obj_w / 2) - ($wm_image_obj_w / 2));
        $src_image_obj_max_x = ceil(($src_image_obj_w / 2) + ($wm_image_obj_w / 2));
        $src_image_obj_min_y = floor(($src_image_obj_h / 2) - ($wm_image_obj_h / 2));
        $src_image_obj_max_y = ceil(($src_image_obj_h / 2) + ($wm_image_obj_h / 2));

        # create new image to hold merged changes
        $return_img = imagecreatetruecolor($src_image_obj_w, $src_image_obj_h);

        # walk through main image
        for ($y = 0; $y < $src_image_obj_h; $y++) {
            for ($x = 0; $x < $src_image_obj_w; $x++) {
                $return_color = NULL;

                # determine the correct pixel location within our watermark
                $watermark_x = $x - $src_image_obj_min_x;
                $watermark_y = $y - $src_image_obj_min_y;

                # fetch color information for both of our images
                $main_rgb = imagecolorsforindex($src_image_obj, imagecolorat($src_image_obj, $x, $y));

                # if our watermark has a non-transparent value at this pixel intersection
                # and we're still within the bounds of the watermark image
                if ($watermark_x >= 0 && $watermark_x < $wm_image_obj_w &&
                    $watermark_y >= 0 && $watermark_y < $wm_image_obj_h
                ) {
                    $watermark_rbg = imagecolorsforindex($wm_image_obj, imagecolorat($wm_image_obj, $watermark_x, $watermark_y));

                    # using image alpha, and user specified alpha, calculate average
                    $watermark_alpha = round(((127 - $watermark_rbg['alpha']) / 127), 2);
                    $watermark_alpha = $watermark_alpha * $alpha_level;

                    # calculate the color 'average' between the two - taking into account the specified alpha level
                    $avg_red = $this->_get_ave_color($main_rgb['red'], $watermark_rbg['red'], $watermark_alpha);
                    $avg_green = $this->_get_ave_color($main_rgb['green'], $watermark_rbg['green'], $watermark_alpha);
                    $avg_blue = $this->_get_ave_color($main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha);

                    # calculate a color index value using the average RGB values we've determined
                    $return_color = $this->_get_image_color($return_img, $avg_red, $avg_green, $avg_blue);

                    # if we're not dealing with an average color here, then let's just copy over the main color
                } else {
                    $return_color = imagecolorat($src_image_obj, $x, $y);
                } # END if watermark
                # draw the appropriate color onto the return image
                imagesetpixel($return_img, $x, $y, $return_color);
            } # END for each X pixel
        } # END for each Y pixel
        # return the resulting, watermarked image for display
        $this->complete_image = $return_img;
        return true;
    }


    /**
     *
     * @return boolean
     */
    public function do_watermark_bottom_right()
    {

        $dest_x = $this->src_width - $this->wm_width - 5;
        $dest_y = $this->src_height - $this->wm_height - 5;
        $new_image = $this->src_image;

        $comp = imagecopy($new_image, $this->wm_image, $dest_x, $dest_y, 0, 0, $this->wm_width, $this->wm_height);

        if ($comp == false) {
            @imagedestroy($new_image);
            return false;
        }

        $this->complete_image = $new_image;

        @imagedestroy($new_image);

        return true;
    }

    /**
     *
     * @return booelan
     */
    public function do_watermark_center()
    {

        $watermark = $this->wm_image;
        $image = $this->src_image;

        $watermark_x = $this->wm_width;
        $watermark_y = $this->wm_height;

        $im_x = $this->src_width;
        $im_y = $this->src_height;

        $cof = $im_x / ($watermark_x * 1.3); // 5/1 = im_x/(wx*cof) ; wx*cof = im_x/5 ; cof = im_x/wx*5
        $w = intval($watermark_x * $cof);
        $h = intval($watermark_y * $cof);

        $watermark_mini = ImageCreateTrueColor($w, $h);
        imagealphablending($watermark_mini, false);
        imagesavealpha($watermark_mini, true);
        ImageCopyResampled($watermark_mini, $watermark, 0, 0, 0, 0, $w, $h, $watermark_x, $watermark_y);

        $dest_x = $im_x - $w - (($im_x - $w) / 2);
        $dest_y = $im_y - $h - (($im_y - $h) / 2);

        $comp = imagecopy($image, $watermark_mini, $dest_x, $dest_y, 0, 0, $w, $h);

        if (!$comp) {
            @imagedestroy($image);
            @imagedestroy($watermark);
            @imagedestroy($watermark_mini);
            return false;
        }

        $this->complete_image = $image;

        @imagedestroy($image);
        @imagedestroy($watermark);
        @imagedestroy($watermark_mini);

        return true;
    }

    /**
     *
     * @param image resource $image
     * @param image resource $watermark
     * @return boolean
     */
    public function do_watermark_bottom_right_small()
    {

        $watermark = $this->wm_image;
        $image = $this->src_image;

        $orig_watermark_x = $this->wm_width;
        $orig_watermark_y = $this->wm_height;

        $im_x = $this->src_width;
        $im_y = $this->src_height;

        $cof = $im_x / ($orig_watermark_x * 5); // 5/1 = im_x/(wx*cof) ; wx*cof = im_x/5 ; cof = im_x/wx*5
        $w = intval($orig_watermark_x * $cof);
        $h = intval($orig_watermark_y * $cof);

        $watermark_mini = ImageCreateTrueColor($w, $h);
        imagealphablending($watermark_mini, false);
        imagesavealpha($watermark_mini, true);
        ImageCopyResampled($watermark_mini, $watermark, 0, 0, 0, 0, $w, $h, $orig_watermark_x, $orig_watermark_y);
        //
        $dest_x = $size[0] - $w - 5;
        $dest_y = $size[1] - $h - 5;

        $comp = imagecopy($image, $watermark_mini, $dest_x, $dest_y, 0, 0, $w, $h);

        if (!$comp) {
            @imagedestroy($image);
            @imagedestroy($watermark);
            @imagedestroy($watermark_mini);
            return false;
        }

        $this->complete_image = $image;

        @imagedestroy($image);
        @imagedestroy($watermark);
        @imagedestroy($watermark_mini);
        return true;
    }


    /**
     * set alpha level
     * @param int $level
     */
    public function set_alpha_level($level = 100)
    {
        $this->alpha_level = $level;
    }


    /**
     * average two colors given an alpha
     *
     * @param int $color_a
     * @param int $color_b
     * @param int $alpha_level
     * @return int
     */
    function _get_ave_color($color_a, $color_b, $alpha_level)
    {
        return round((($color_a * (1 - $alpha_level)) + ($color_b * $alpha_level)));
    }

    /**
     * return closest pallette-color match for RGB values
     * @param resouce $im
     * @param int $r
     * @param ing $g
     * @param int $b
     */
    function _get_image_color($im, $r, $g, $b)
    {
        $c = imagecolorexact($im, $r, $g, $b);
        if ($c != -1)
            return $c;
        $c = imagecolorallocate($im, $r, $g, $b);
        if ($c != -1)
            return $c;
        return imagecolorclosest($im, $r, $g, $b);
    }


}