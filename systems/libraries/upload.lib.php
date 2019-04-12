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

class upload
{
    public $___FILE = array();
    public $set_data = "";
    public $max_size = 0;
    public $max_height = 0;
    public $max_width = 0;
    public $max_filename = 0;
    public $allowed_types = "";
    public $not_allowed_types = "";
    public $file_temp = "";
    public $file_name = "";
    public $orig_name = "";
    public $file_type = "";
    public $file_size = "";
    public $file_ext = "";
    public $upload_path = "";
    public $overwrite = FALSE;
    public $encrypt_name = FALSE;
    public $is_image = FALSE;
    public $image_width = '';
    public $image_height = '';
    public $image_type = '';
    public $image_size_str = '';
    public $error_msg = array();
    public $mimes = array();
    public $remove_spaces = TRUE;
    public $xss_clean = FALSE;
    public $temp_prefix = "temp_file_";
    public $client_name = '';
    public $file_name_set;
    protected $_file_name_override = '';
    public $error = array();
    protected $is_upload_by_file = false;
    protected $is_use_ssl = false;
    public $disablade_check_file_exists = false;
    public $seo_name = true;

    /**
     * Constructor
     *
     * @access    public
     */
    public function __construct($props = array())
    {

        $this->load_defaults_config();
    }

    // --------------------------------------------------------------------

    /**
     *
     * @global array $system_config
     */
    public function load_defaults_config()
    {
        global $system_config;

        $this->allowed_types = $system_config['exts'];
        $this->max_size = $system_config['max_file_size'];
    }

    /**
     * Initialize preferences
     *
     * @param    array
     * @return    void
     */
    public function initialize($config = array())
    {
        global $system_config;

        $defaults = array(
            'max_size' => $system_config['max_file_size'],
            'max_width' => 0,
            'max_height' => 0,
            'max_filename' => 0,
            'allowed_types' => '',
            'not_allowed_types' => '',
            'file_temp' => "",
            'file_name' => "",
            'orig_name' => "",
            'file_type' => "",
            'file_size' => "",
            'file_ext' => "",
            'upload_path' => "",
            'overwrite' => FALSE,
            'encrypt_name' => FALSE,
            'is_image' => FALSE,
            'image_width' => '',
            'image_height' => '',
            'image_type' => '',
            'image_size_str' => '',
            'error_msg' => array(),
            'mimes' => array(),
            'remove_spaces' => TRUE,
            'xss_clean' => FALSE,
            'temp_prefix' => "temp_file_",
            'client_name' => '',
            'seo_name' => TRUE
        );


        foreach ($defaults as $key => $val) {

            if (isset($config[$key])) {

                $method = 'set_' . $key;

                if (method_exists($this, $method)) {

                    $this->$method($config[$key]);
                } else {

                    $this->$key = $config[$key];
                }

            } else {
                $this->$key = $val;
            }
        }

        /**
         * if a file_name was provided in the config, use it instead of the user input
         * supplied file name for all uploads until initialized again
         */
        $this->_file_name_override = $this->file_name;
    }

    /**
     *
     * @param string $url
     * @return boolean
     */
    public function url_exists($url)
    {
        $file_headers = $this->get_headers($url, true);

        if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {

            $exists = false;
        } else {

            $exists = true;
        }
        return $exists;
    }

    /**
     *
     * @param string $url
     * @return int
     */
    function remote_file_size($url)
    {
        /**
         * Get all header information
         */
        $data = $this->get_headers($url, true);
        /**
         * Look up validity
         */
        if (isset($data['Content-Length'])) {
            /**
             * Return file size
             */
            return (int)$data['Content-Length'];
        }
    }

    /**
     *
     * @param string $url
     * @param boolean $format
     * @return array
     */
    function get_headers($url, $format = false)
    {

        /**
         * If support get_headers function
         */
        if (function_exists('get_headers')) {

            $out = get_headers($url, $format);

            $a = explode(';', $out['Content-Type']);

            if (isset($a[0])) {

                $val2 = $a[0];
            } else {

                $val2 = $out['Content-Type'];
            }
            $out['Content-mime-Type'] = $val2;

            return $out;
        }

        /**
         * If support curl
         */
        if (function_exists('curl_version')) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL verifying
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            $r = curl_exec($ch);
            $r = @split("\n", $r);

            foreach ($r as $line) {

                @list($key, $val) = explode(': ', $line, 2);
                if ($format)
                    if ($val) {
                        if ($key == 'Content-Type') {

                            $a = explode(';', $val);

                            if (isset($a[0])) {
                                $val2 = $a[0];
                            } else {
                                $val2 = $val;
                            }
                            $headers['Content-mime-Type'] = trim($val2);
                        }
                        $headers[$key] = trim($val);
                    } else
                        $headers[] = $key;
                else
                    $headers[] = $line;
            }

            return $headers;
        }

        $headers = array();
        $url = parse_url($url);
        $host = isset($url['host']) ? $url['host'] : '';
        $port = isset($url['port']) ? $url['port'] : 80;
        $path = (isset($url['path']) ? $url['path'] : '/') . (isset($url['query']) ? '?' . $url['query'] : '');

        $fp = fsockopen($host, $port, $errno, $errstr, 3);
        if ($fp) {

            $hdr = "GET $path HTTP/1.1\r\n";
            $hdr .= "Host: $host \r\n";
            $hdr .= "Connection: Close\r\n\r\n";

            fwrite($fp, $hdr);
            while (!feof($fp) && $line = trim(fgets($fp, 1024))) {

                if ($line == "\r\n")
                    break;
                @list($key, $val) = explode(': ', $line, 2);
                if ($format)

                    if ($val) {

                        if ($key == 'Content-Type') {
                            $a = explode(';', $val);
                            if (isset($a[0])) {
                                $val2 = $a[0];
                            } else {
                                $val2 = $val;
                            }
                            $headers['Content-mime-Type'] = trim($val2);
                        }
                        $headers[$key] = trim($val);
                    } else
                        $headers[] = $key;
                else
                    $headers[] = $line;
            }
            fclose($fp);
            return $headers;
        }
        return false;
    }

    /**
     *
     * @param string $url
     * @return brinary
     */
    function file_get_contents($url)
    {

        $output = @file_get_contents($url);

        if ($output) {
            return $output;
        }
        $this->is_use_ssl = TRUE;
        if (!function_exists('curl_version')) {

            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL verifying
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    // --------------------------------------------------------------------

    /**
     *
     * @param string $data
     */
    public function set_data($data)
    {
        $this->set_data = $data;
        $this->disablade_check_file_exists == true;
    }

    public function multiple($field = false, $type = FILE_INPUT)
    {

        $uploaded_fields = array();

        if ($type == FILE_INPUT) {

            if (!$field) {

                $main_key = key($_FILES);
            } else {

                $main_key = $field;
            }

            if (isset($_FILES[$main_key])) {

                if (is_array($_FILES[$main_key]['name'])) {

                    foreach ($_FILES[$main_key] as $key => $value) {

                        for ($y = 0; $y < count($value); $y++) {

                            $keyname = $main_key . '-' . $y;

                            $_FILES[$keyname][$key] = $value[$y];

                            $uploaded_fields[] = $keyname;
                        }
                    }
                    unset($_FILES[$main_key]);

                } else {

                    $uploaded_fields[] = $field;
                }

            }

        } else {

            if (isset($_POST[$field])) {

                if (is_array($_POST[$field])) {

                    foreach ($_POST[$field] as $key => $value) {

                        $keyname = $field . '-' . $key;

                        $_POST[$keyname] = $value;

                        $uploaded_fields[] = $keyname;

                        unset($_POST[$field][$key]);
                    }

                    unset($_POST[$field]);

                } else {

                    $uploaded_fields[] = $field;
                }

                foreach ($uploaded_fields as $k => $val) {

                    if (is_null($_POST[$val]) || empty($_POST[$val])) {

                        unset($uploaded_fields[$k]);
                    }
                }
            }
        }

        $uploaded_fields = array_unique($uploaded_fields);

        return $uploaded_fields;

    }

    /**
     * Perform the file upload
     *
     * @param string $field
     * @return bool
     */
    public function do_upload($field = 'userfile')
    {
        if (!empty($this->set_data)) {

            $_POST[$field] = $this->set_data;
        }

        /**
         * Is $_FILES[$field] set? If not, no reason to continue.
         */
        if (empty($_FILES[$field]['name']) && empty($_POST[$field]) && $this->disablade_check_file_exists == FALSE) {

            $this->set_error('Chưa có file nào được chọn');

            return FALSE;

        } elseif (empty($_FILES[$field]['name']) && !empty($_POST[$field])) {

            if (!$this->url_exists($_POST[$field])) {

                $this->set_error('Địa chỉ file không chính xác');
                return FALSE;

            }

            $this->___FILE[$field]['resource'] = $_POST[$field];
            $this->___FILE[$field]['tmp_name'] = $this->file_get_contents($_POST[$field]);
            $this->___FILE[$field]['size'] = $this->remote_file_size($_POST[$field]);
            $this->___FILE[$field]['name'] = basename($_POST[$field]);

            $file_info_header = $this->get_headers($_POST[$field], TRUE);

            if (isset($file_info_header['Content-mime-Type'])) {

                $this->___FILE[$field]['type'] = $file_info_header['Content-mime-Type'];
            }

            if (empty($this->___FILE[$field]['tmp_name'])) {

                $this->set_error('Không thể lấy thông tin file');
                return false;
            }

        } else {

            $this->___FILE = $_FILES;
            $this->is_upload_by_file = TRUE;

        }

        /**
         * Is the upload path valid?
         */
        if (!$this->validate_upload_path()) {
            /**
             * errors will already be set by validate_upload_path() so just return FALSE
             */
            return FALSE;
        }

        if ($this->is_upload_by_file == TRUE) {

            /**
             * Was the file able to be uploaded? If not, determine the reason why.
             */
            if (!is_uploaded_file($this->___FILE[$field]['tmp_name'])) {

                $error = (!isset($this->___FILE[$field]['error'])) ? 4 : $this->___FILE[$field]['error'];

                switch ($error) {
                    case 1: // UPLOAD_ERR_INI_SIZE
                        $this->set_error('Tập tin vượt quá giới hạn');
                        break;
                    case 2: // UPLOAD_ERR_FORM_SIZE
                        $this->set_error('Tập tin vượt quá giới hạn của form');
                        break;
                    case 3: // UPLOAD_ERR_PARTIAL
                        $this->set_error('Error Upload file partial');
                        break;
                    case 4: // UPLOAD_ERR_NO_FILE
                        $this->set_error('Không có file nào được chọn');
                        break;
                    case 6: // UPLOAD_ERR_NO_TMP_DIR
                        $this->set_error('Không có thư mục upload');
                        break;
                    case 7: // UPLOAD_ERR_CANT_WRITE
                        $this->set_error('Không thể ghi file');
                        break;
                    case 8: // UPLOAD_ERR_EXTENSION
                        $this->set_error('Tập tin tải lên đã bị dừng lại');
                        break;
                    default :
                        $this->set_error('Không có file nào được chọn');
                        break;
                }

                return FALSE;
            }
        }


        /**
         * Set the uploaded data as class variables
         */
        if (isset($this->___FILE[$field]['resource'])) {

            $this->file_resource = $this->___FILE[$field]['resource'];
        }

        $this->file_temp = $this->___FILE[$field]['tmp_name'];
        $this->file_size = $this->___FILE[$field]['size'];
        $this->_file_mime_type($this->___FILE[$field]);
        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));

        if (empty($this->file_name_set)) {

            $this->file_name = $this->_prep_filename($this->___FILE[$field]['name']);

        } else {

            $this->file_name = $this->file_name_set . $this->get_extension($this->___FILE[$field]['name']);
        }

        $this->file_ext = $this->get_extension($this->file_name);

        $this->client_name = $this->file_name;

        /**
         * Is the file type allowed to be uploaded?
         */
        if (!$this->is_allowed_filetype()) {

            $this->set_error('Không cho phép upload định dạng file này: ' . $this->file_type);
            return FALSE;
        }

        /**
         * if we're overriding, let's now make sure the new name and type is allowed
         */
        if ($this->_file_name_override != '') {

            $this->file_name = $this->_prep_filename($this->_file_name_override);

            /**
             * If no extension was provided in the file_name config item, use the uploaded one
             */
            if (strpos($this->_file_name_override, '.') === FALSE) {
                $this->file_name .= $this->file_ext;
            } // An extension was provided, lets have it!
            else {
                $this->file_ext = $this->get_extension($this->_file_name_override);
            }

            if (!$this->is_allowed_filetype(TRUE)) {
                $this->set_error('Định dạng file không cho phép');
                return FALSE;
            }
        }

        /**
         * Is the file size within the allowed maximum?
         */
        if (!$this->is_allowed_filesize()) {
            $this->set_error('Kích thước file không cho phép');
            return FALSE;
        }

        /**
         * Are the image dimensions within the allowed size?
         * Note: This can fail if the server has an open_basdir restriction.
         */
        if (!$this->is_allowed_dimensions()) {

            $this->set_error('Kích thước không hợp lệ');
            return FALSE;
        }

        /**
         * Sanitize the file name for security
         */
        $this->file_name = $this->clean_file_name($this->file_name);

        if ($this->seo_name == true) {

            if (function_exists('load_library')) {

                $seo = load_library('seo');

                $match_ext = str_replace(array('.', '/', '-'), array('\.', '\/', '\-'), $this->file_ext);

                $need = preg_replace('/' . $match_ext . '$/is', '', $this->file_name);

                $this->file_name = $seo->url($need) . $this->file_ext;

            }
        }

        /**
         * Truncate the file name if it's too long
         */
        if ($this->max_filename > 0) {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }

        /**
         * Remove white spaces in the name
         */
        if ($this->remove_spaces == TRUE) {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }

        /**
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;

        if ($this->overwrite == FALSE) {
            $this->file_name = $this->set_filename($this->upload_path, $this->file_name);

            if ($this->file_name === FALSE) {
                return FALSE;
            }
        }

        /**
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file.  Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean) {
            if ($this->do_xss_clean() === FALSE) {
                $this->set_error('Không thể ghi tập tin');
                return FALSE;
            }
        }

        /**
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first.  If that fails
         * we'll use move_uploaded_file().  One of the two should
         * reliably work in most environments
         */
        if (!@copy($this->file_temp, $this->upload_path . $this->file_name)) {
            if ($this->is_upload_by_file == TRUE) {
                if (!@move_uploaded_file($this->file_temp, $this->upload_path . $this->file_name)) {
                    $this->set_error('Không thể di chuyển file');
                    return FALSE;
                }
            } else {
                if (!@file_put_contents($this->upload_path . $this->file_name, $this->file_temp)) {
                    $this->set_error('Không thể di chuyển file');
                    return FALSE;
                }
            }
        }

        /**
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        $this->set_image_properties($this->upload_path . $this->file_name);
        if (!empty($this->set_data)) {
            unset($this->set_data);
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Finalized Data Array
     *
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @return    array
     */
    public function data()
    {
        return array(
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_path' => $this->upload_path,
            'full_path' => $this->upload_path . $this->file_name,
            'raw_name' => str_replace($this->file_ext, '', $this->file_name),
            'orig_name' => $this->orig_name,
            'client_name' => $this->client_name,
            'file_ext' => $this->file_ext,
            'file_size' => $this->file_size,
            'is_image' => $this->is_image(),
            'image_width' => $this->image_width,
            'image_height' => $this->image_height,
            'image_type' => $this->image_type,
            'image_size_str' => $this->image_size_str,
        );
    }

    // --------------------------------------------------------------------

    /**
     *
     * @param string $name
     */
    public function set_file_name($name)
    {
        if (!empty($name)) {
            $this->file_name_set = $name;
        }
    }

    /**
     * Set Upload Path
     *
     * @param    string
     * @return    void
     */
    public function set_upload_path($path)
    {
        /**
         * Make sure it has a trailing slash
         */
        $this->upload_path = rtrim($path, '/') . '/';
    }

    // --------------------------------------------------------------------

    /**
     * Set the file name
     *
     * This function takes a filename/path as input and looks for the
     * existence of a file with the same name. If found, it will append a
     * number to the end of the filename to avoid overwriting a pre-existing file.
     *
     * @param    string
     * @param    string
     * @return    string
     */
    public function set_filename($path, $filename)
    {
        if ($this->encrypt_name == TRUE) {
            mt_srand();
            $filename = md5(uniqid(mt_rand())) . $this->file_ext;
        }

        if (!file_exists($path . $filename)) {
            return $filename;
        }

        $filename = str_replace($this->file_ext, '', $filename);

        $new_filename = '';
        for ($i = 1; $i < 100; $i++) {
            if (!file_exists($path . $filename . $i . $this->file_ext)) {
                $new_filename = $filename . $i . $this->file_ext;
                break;
            }
        }

        if ($new_filename == '') {
            $this->set_error('Tên file không hợp lệ');
            return FALSE;
        } else {
            return $new_filename;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum File Size
     *
     * @param    integer
     * @return    void
     */
    public function set_max_filesize($n)
    {
        $this->max_size = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum File Name Length
     *
     * @param    integer
     * @return    void
     */
    public function set_max_filename($n)
    {
        $this->max_filename = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum Image Width
     *
     * @param    integer
     * @return    void
     */
    public function set_max_width($n)
    {
        $this->max_width = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum Image Height
     *
     * @param    integer
     * @return    void
     */
    public function set_max_height($n)
    {
        $this->max_height = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Allowed File Types
     *
     * @param    string
     * @return    void
     */
    public function set_allowed_types($types)
    {
        if (!is_array($types) && $types == '*') {

            $this->allowed_types = '*';
            return;
        }
        if (!is_array($types)) {

            $this->allowed_types = explode('|', $types);

        } else {

            $this->allowed_types = $types;
        }

    }

    /**
     * Set Allowed File Types
     *
     * @param    string
     * @return    void
     */
    public function set_not_allowed_types($types)
    {
        if (!is_array($types) && $types == '*') {

            $this->allowed_types = '';
            return;
        }
        if (!is_array($types)) {

            $this->not_allowed_types = explode('|', $types);
        } else {

            $this->not_allowed_types = $types;
        }

    }

    // --------------------------------------------------------------------

    /**
     * Set Image Properties
     *
     * Uses GD to determine the width/height/type of image
     *
     * @param    string
     * @return    void
     */
    public function set_image_properties($path = '')
    {
        if (!$this->is_image()) {
            return;
        }

        if (function_exists('getimagesize')) {
            if (FALSE !== ($D = @getimagesize($path))) {
                $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

                $this->image_width = $D['0'];
                $this->image_height = $D['1'];
                $this->image_type = (!isset($types[$D['2']])) ? 'unknown' : $types[$D['2']];
                $this->image_size_str = $D['3']; // string containing height and width
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set XSS Clean
     *
     * Enables the XSS flag so that the file that was uploaded
     * will be run through the XSS filter.
     *
     * @param    bool
     * @return    void
     */
    public function set_xss_clean($flag = FALSE)
    {
        $this->xss_clean = ($flag == TRUE) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate the image
     *
     * @return    bool
     */
    public function is_image()
    {
        /**
         * IE will sometimes return odd mime-types during upload, so here we just standardize all
         * jpegs or pngs to the same file type.
         */
        $png_mimes = array('image/png', 'image/x-png');
        $jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');
        $bmp_mimes = array('image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap',
            'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp',
            'application/x-bmp', 'application/x-win-bitmap');
        $gif_mimes = array('image/gif');

        $ico_mimes = array('image/ico', 'image/x-icon', 'application/ico', 'application/x-ico');

        if (in_array($this->file_type, $png_mimes)) {
            $this->file_type = 'image/png';
        }

        if (in_array($this->file_type, $jpeg_mimes)) {
            $this->file_type = 'image/jpeg';
        }

        if (in_array($this->file_type, $bmp_mimes)) {
            $this->file_type = 'image/bmp';
        }

        if (in_array($this->file_type, $gif_mimes)) {
            $this->file_type = 'image/gif';
        }

        if (in_array($this->file_type, $ico_mimes)) {
            $this->file_type = 'image/ico';
        }

        $img_mimes = array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/bmp',
            'image/gif',
            'image/ico',
        );

        return (in_array($this->file_type, $img_mimes, TRUE)) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------


    /**
     * Verify that the filetype is allowed
     *
     * @param bool $ignore_mime
     * @return bool
     */
    public function is_allowed_filetype($ignore_mime = FALSE)
    {

        $this->set_allowed_types($this->allowed_types);

        $this->set_not_allowed_types($this->not_allowed_types);

        if ($this->allowed_types == '*' && empty($this->not_allowed_types)) {

            return TRUE;
        }

        if (count($this->allowed_types) == 0 OR !is_array($this->allowed_types)) {

            $this->set_error('Hiện tại chưa cho phép upload');
            return FALSE;
        }

        $ext = strtolower(ltrim($this->file_ext, '.'));

        if (!empty($this->not_allowed_types)) {

            if (in_array($ext, $this->not_allowed_types)) {

                return FALSE;
            }
        }

        if (!empty($this->not_allowed_types)) {

            foreach ($this->allowed_types as $k => $tp) {

                if (in_array($tp, $this->not_allowed_types)) {

                    unset($this->allowed_types[$k]);
                }
            }
        }

        if (!in_array($ext, $this->allowed_types)) {

            return FALSE;
        }

        /**
         * Images get some additional checks
         */
        $image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe', 'bmp', 'ico');


        if (in_array($ext, $image_types)) {
            if ($this->is_upload_by_file == true) {
                if (@getimagesize($this->file_temp) === FALSE) {
                    return FALSE;
                }
            } else {
                if (@getimagesize($this->file_resource) === FALSE) {
                    $im = imagecreatefromstring($this->file_temp);
                    if (!$im) {
                        return FALSE;
                    }
                }
            }
        }

        if ($ignore_mime === TRUE) {
            return TRUE;
        }

        $mime = $this->mimes_types($ext);

        if (is_array($mime)) {

            if (in_array(trim($this->file_type), $mime, TRUE)) {

                return TRUE;
            }
        } elseif ($mime == trim($this->file_type)) {
            return TRUE;
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Verify that the file is within the allowed size
     *
     * @return    bool
     */
    public function is_allowed_filesize()
    {
        global $system_config;
        if ($this->max_size == 0) {
            $this->max_size = $system_config['max_file_size'];
        }
        if ($this->max_size != 0 AND $this->file_size > $this->max_size) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Verify that the image is within the allowed width/height
     *
     * @return    bool
     */
    public function is_allowed_dimensions()
    {
        if (!$this->is_image()) {
            return TRUE;
        }

        if (function_exists('getimagesize')) {
            $D = @getimagesize($this->file_temp);

            if ($this->max_width > 0 AND $D['0'] > $this->max_width) {
                return FALSE;
            }

            if ($this->max_height > 0 AND $D['1'] > $this->max_height) {
                return FALSE;
            }

            return TRUE;
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate Upload Path
     *
     * Verifies that it is a valid upload path with proper permissions.
     *
     *
     * @return    bool
     */
    public function validate_upload_path()
    {
        if ($this->upload_path == '') {
            $this->set_error('Không có đường dẫn upload');
            return FALSE;
        }

        if (function_exists('realpath') AND @realpath($this->upload_path) !== FALSE) {
            $this->upload_path = str_replace("\\", "/", realpath($this->upload_path));
        }

        if (!@is_dir($this->upload_path)) {
            $this->set_error('Đường dẫn upload sai');
            return FALSE;
        }

        if (!is_really_writable($this->upload_path)) {
            $this->set_error('Không thể ghi file vào thư mục này');
            return FALSE;
        }

        $this->upload_path = preg_replace("/(.+?)\/*$/", "\\1/", $this->upload_path);
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Extract the file extension
     *
     * @param    string
     * @return    string
     */
    public function get_extension($filename)
    {
        $x = explode('.', $filename);
        return '.' . end($x);
    }

    // --------------------------------------------------------------------

    /**
     * Clean the file name for security
     *
     * @param    string
     * @return    string
     */
    public function clean_file_name($filename)
    {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c", // <
            "%253c", // <
            "%3e", // >
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d" // =
        );

        $filename = str_replace($bad, '', $filename);

        return stripslashes($filename);
    }

    // --------------------------------------------------------------------

    /**
     * Limit the File Name Length
     *
     * @param $filename
     * @param $length
     * @return string
     */
    public function limit_filename_length($filename, $length)
    {
        if (strlen($filename) < $length) {
            return $filename;
        }

        $ext = '';
        if (strpos($filename, '.') !== FALSE) {
            $parts = explode('.', $filename);
            $ext = '.' . array_pop($parts);
            $filename = implode('.', $parts);
        }

        return substr($filename, 0, ($length - strlen($ext))) . $ext;
    }

    // --------------------------------------------------------------------

    /**
     * Runs the file through the XSS clean function
     *
     * This prevents people from embedding malicious code in their files.
     * I'm not sure that it won't negatively affect certain files in unexpected ways,
     * but so far I haven't found that it causes trouble.
     *
     * @return    void
     */
    public function do_xss_clean()
    {
        $file = $this->file_temp;

        if (filesize($file) == 0) {
            return FALSE;
        }

        if (function_exists('memory_get_usage') && memory_get_usage() && ini_get('memory_limit') != '') {
            $current = ini_get('memory_limit') * 1024 * 1024;

            // There was a bug/behavioural change in PHP 5.2, where numbers over one million get output
            // into scientific notation.  number_format() ensures this number is an integer
            // http://bugs.php.net/bug.php?id=43053

            $new_memory = number_format(ceil(filesize($file) + $current), 0, '.', '');

            ini_set('memory_limit', $new_memory); // When an integer is used, the value is measured in bytes. - PHP.net
        }

        // If the file being uploaded is an image, then we should have no problem with XSS attacks (in theory), but
        // IE can be fooled into mime-type detecting a malformed image as an html file, thus executing an XSS attack on anyone
        // using IE who looks at the image.  It does this by inspecting the first 255 bytes of an image.  To get around this
        // CI will itself look at the first 255 bytes of an image to determine its relative safety.  This can save a lot of
        // processor power and time if it is actually a clean image, as it will be in nearly all instances _except_ an
        // attempted XSS attack.

        if (function_exists('getimagesize') && @getimagesize($file) !== FALSE) {
            if (($file = @fopen($file, 'rb')) === FALSE) { // "b" to force binary
                return FALSE; // Couldn't open the file, return FALSE
            }

            $opening_bytes = fread($file, 256);
            fclose($file);

            // These are known to throw IE into mime-type detection chaos
            // <a, <body, <head, <html, <img, <plaintext, <pre, <script, <table, <title
            // title is basically just in SVG, but we filter it anyhow

            if (!preg_match('/<(a|body|head|html|img|plaintext|pre|script|table|title)[\s>]/i', $opening_bytes)) {
                return TRUE; // its an image, no "triggers" detected in the first 256 bytes, we're good
            } else {
                return FALSE;
            }
        }

        if (($data = @file_get_contents($file)) === FALSE) {
            return FALSE;
        }

        if (function_exists('load_library')) {

            $ZenSecurity = load_library('security');


            $data = $ZenSecurity->cleanXSS($data, TRUE);
        }

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Set an error message
     *
     * @param    string
     * @return    void
     */
    public function set_error($msg)
    {
        $this->error[] = $msg;
    }

    // --------------------------------------------------------------------

    /**
     * Display the error message
     *
     * @param    string
     * @param    string
     * @return    string
     */
    public function display_errors($open = '<p>', $close = '</p>')
    {
        $str = '';
        foreach ($this->error as $val) {
            $str .= $open . $val . $close;
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * List of Mime Types
     *
     * This is a list of mime types.  We use it to validate
     * the "allowed types" set by the developer
     *
     * @param    string
     * @return    string
     */
    public function mimes_types($mime)
    {
        global $system_config;

        if (count($this->mimes) == 0) {

            $this->mimes = $system_config['mimes'];
        }

        return (!isset($this->mimes[$mime])) ? FALSE : $this->mimes[$mime];
    }

    // --------------------------------------------------------------------

    /**
     * Prep Filename
     *
     * Prevents possible script execution from Apache's handling of files multiple extensions
     * http://httpd.apache.org/docs/1.3/mod/mod_mime.html#multipleext
     *
     * @param    string
     * @return    string
     */
    protected function _prep_filename($filename)
    {
        if (strpos($filename, '.') === FALSE OR $this->allowed_types == '*') {
            return $filename;
        }

        $parts = explode('.', $filename);
        $ext = array_pop($parts);
        $filename = array_shift($parts);

        foreach ($parts as $part) {
            if (!in_array(strtolower($part), $this->allowed_types) OR $this->mimes_types(strtolower($part)) === FALSE) {
                $filename .= '.' . $part . '_';
            } else {
                $filename .= '.' . $part;
            }
        }

        $filename .= '.' . $ext;

        return $filename;
    }

    // --------------------------------------------------------------------

    /**
     * File MIME type
     *
     * Detects the (actual) MIME type of the uploaded file, if possible.
     * The input array is expected to be $this->___FILE[$field]
     *
     * @param    array
     * @return    void
     */
    protected function _file_mime_type($file)
    {
        //var_dump($file);
        // We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
        $regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

        /* Fileinfo extension - most reliable method
         *
         * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
         * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
         */
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME);
            if (is_resource($finfo)) { // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
                $mime = @finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                /* According to the comments section of the PHP manual page,
                 * it is possible that this function returns an empty string
                 * for some files (e.g. if they don't exist in the magic MIME database)
                 */
                if (is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $this->file_type = $matches[1];
                    return;
                }
            }
        }


        if (DIRECTORY_SEPARATOR !== '\\') {
            $cmd = 'file --brief --mime ' . escapeshellarg($file['tmp_name']) . ' 2>&1';

            if (function_exists('exec')) {
                /* This might look confusing, as $mime is being populated with all of the output when set in the second parameter.
                 * However, we only neeed the last line, which is the actual return value of exec(), and as such - it overwrites
                 * anything that could already be set for $mime previously. This effectively makes the second parameter a dummy
                 * value, which is only put to allow us to get the return status code.
                 */
                $mime = @exec($cmd, $mime, $return_status);
                if ($return_status === 0 && is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $this->file_type = $matches[1];
                    return;
                }
            }

            if ((bool)@ini_get('safe_mode') === FALSE && function_exists('shell_exec')) {
                $mime = @shell_exec($cmd);
                if (strlen($mime) > 0) {
                    $mime = explode("\n", trim($mime));
                    if (preg_match($regexp, $mime[(count($mime) - 1)], $matches)) {
                        $this->file_type = $matches[1];
                        return;
                    }
                }
            }

            if (function_exists('popen')) {
                $proc = @popen($cmd, 'r');
                if (is_resource($proc)) {
                    $mime = @fread($proc, 512);
                    @pclose($proc);
                    if ($mime !== FALSE) {
                        $mime = explode("\n", trim($mime));
                        if (preg_match($regexp, $mime[(count($mime) - 1)], $matches)) {
                            $this->file_type = $matches[1];
                            return;
                        }
                    }
                }
            }
        }

        // Fall back to the deprecated mime_content_type(), if available (still better than $this->___FILE[$field]['type'])
        if (function_exists('mime_content_type')) {
            $this->file_type = @mime_content_type($file['tmp_name']);
            if (strlen($this->file_type) > 0) { // It's possible that mime_content_type() returns FALSE or an empty string
                return;
            }
        }

        if (isset($file['type'])) {
            $this->file_type = $file['type'];
        } else {
            $this->file_type = '';
        }
    }

    // --------------------------------------------------------------------

    function get_file_extension($file)
    {

        return array_pop(explode('.', $file));
    }

    function get_mimetype($value = '')
    {

        $ct['htm'] = 'text/html';
        $ct['html'] = 'text/html';
        $ct['txt'] = 'text/plain';
        $ct['asc'] = 'text/plain';
        $ct['bmp'] = 'image/bmp';
        $ct['gif'] = 'image/gif';
        $ct['jpeg'] = 'image/jpeg';
        $ct['jpg'] = 'image/jpeg';
        $ct['jpe'] = 'image/jpeg';
        $ct['png'] = 'image/png';
        $ct['ico'] = 'image/vnd.microsoft.icon';
        $ct['mpeg'] = 'video/mpeg';
        $ct['mpg'] = 'video/mpeg';
        $ct['mpe'] = 'video/mpeg';
        $ct['qt'] = 'video/quicktime';
        $ct['mov'] = 'video/quicktime';
        $ct['avi'] = 'video/x-msvideo';
        $ct['wmv'] = 'video/x-ms-wmv';
        $ct['mp2'] = 'audio/mpeg';
        $ct['mp3'] = 'audio/mpeg';
        $ct['rm'] = 'audio/x-pn-realaudio';
        $ct['ram'] = 'audio/x-pn-realaudio';
        $ct['rpm'] = 'audio/x-pn-realaudio-plugin';
        $ct['ra'] = 'audio/x-realaudio';
        $ct['wav'] = 'audio/x-wav';
        $ct['css'] = 'text/css';
        $ct['zip'] = 'application/zip';
        $ct['pdf'] = 'application/pdf';
        $ct['doc'] = 'application/msword';
        $ct['bin'] = 'application/octet-stream';
        $ct['exe'] = 'application/octet-stream';
        $ct['class'] = 'application/octet-stream';
        $ct['dll'] = 'application/octet-stream';
        $ct['xls'] = 'application/vnd.ms-excel';
        $ct['ppt'] = 'application/vnd.ms-powerpoint';
        $ct['wbxml'] = 'application/vnd.wap.wbxml';
        $ct['wmlc'] = 'application/vnd.wap.wmlc';
        $ct['wmlsc'] = 'application/vnd.wap.wmlscriptc';
        $ct['dvi'] = 'application/x-dvi';
        $ct['spl'] = 'application/x-futuresplash';
        $ct['gtar'] = 'application/x-gtar';
        $ct['gzip'] = 'application/x-gzip';
        $ct['js'] = 'application/x-javascript';
        $ct['swf'] = 'application/x-shockwave-flash';
        $ct['tar'] = 'application/x-tar';
        $ct['xhtml'] = 'application/xhtml+xml';
        $ct['au'] = 'audio/basic';
        $ct['snd'] = 'audio/basic';
        $ct['midi'] = 'audio/midi';
        $ct['mid'] = 'audio/midi';
        $ct['m3u'] = 'audio/x-mpegurl';
        $ct['tiff'] = 'image/tiff';
        $ct['tif'] = 'image/tiff';
        $ct['rtf'] = 'text/rtf';
        $ct['wml'] = 'text/vnd.wap.wml';
        $ct['wmls'] = 'text/vnd.wap.wmlscript';
        $ct['xsl'] = 'text/xml';
        $ct['xml'] = 'text/xml';

        $type = '';

        $extension = $this->get_file_extension($value);

        if (isset($ct[strtolower($extension)])) {

            if ($type != $ct[strtolower($extension)]) {

                $type = 'text/html';
            }
        }
        return $type;
    }

}
