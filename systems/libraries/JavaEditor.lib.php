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

Class JavaEditor
{
    public $zip;
    public $file;
    public $mf_file;
    public $mf_need_editor = array();

    public $file_found_sms = array();

    public function __construct()
    {
        $this->mf_file = 'META-INF/MANIFEST.MF';
        $this->mf_need_editor = array('MIDlet-Delete-Confirm' => 'MIDlet-Delete-Confirm: ' . get_config('delete_confirm_java'),
            'MIDlet-Info-URL' => 'MIDlet-Info-URL: ' . _HOME);
    }

    /**
     * Load file jar
     *
     * @param $file
     * @return bool
     */
    public function loader($file)
    {
        if (!file_exists($file)) {
            return false;
        }
        $this->file = $file;
        $this->zip = load_library('pclzip');
        $this->zip->PclZip($file);
    }


    /**
     * this function will be edit file MANIFEST.MF
     * if MIDlet-Delete-Confirm or MIDlet-Info-URL are exists.
     * Ít will replace as new value
     *
     * @return bool
     */
    public function edit_mf()
    {
        if (empty($this->file) || empty($this->zip)) {
            return false;
        }

        /**
         * get content file mf
         */
        $content = $this->getFileContent($this->mf_file);

        /**
         * get list line
         */
        $list_line = $this->getListLine($content);

        /**
         * find and remove content
         */
        foreach ($list_line as $k => $line) {

            foreach ($this->mf_need_editor as $need => $val) {

                if (preg_match("/^$need/is", $line)) {

                    $list_line[$k] = $val;
                    $$need = true;
                }
            }
        }

        foreach ($this->mf_need_editor as $need => $val) {

            if (!isset($$need)) {

                $list_line[] = $val;
            }
        }

        /**
         * create new content
         */
        $new_content = implode("\r\n", $list_line);

        if ($this->addFromString($this->mf_file, $new_content)) {
            return true;
        }

        return false;
    }

    /**
     * Check java has sms
     *
     * @return bool
     */
    function sms_exist()
    {

        if (empty($this->file) || empty($this->zip)) {
            return false;
        }

        $lists = $this->zip->listContent();

        foreach ($lists as $info) {

            $ext = get_ext($info['filename']);

            if ($ext == 'class') {

                $get = $this->getFileContent($info['filename']);

                $arr = explode("send", $get);

                if (count($arr) != 1) {

                    $this->set_found_sms($info['filename']);
                }
            }
        }
        $this->zip->add(PCLZIP_OPT_ADD_PATH, 'test');
        if (!empty($this->file_found_sms)) {

            return true;
        }
        return false;
    }

    /**
     * If it found class has sms
     * This function will auto crack sms java
     *
     * @return bool
     */
    public function crack()
    {

        if (empty($this->file) || empty($this->zip)) {
            return false;
        }
        if (!$this->sms_exist()) {
            return true;
        }

        foreach ($this->file_found_sms as $f) {

            $content = $this->getFileContent($f);

            $new_content = str_ireplace("javax/microedition/io/Connector", "encode/microedition/io/Connecto", $content);

            $this->addFromString($f, $new_content);

        }

        $file_crack = __SITE_PATH . "/files/systems/java/crack_java/encode/microedition/io/Connecto.class";

        $find_file = "encode/microedition/io/Connecto.class";

        $this->zip->delete(PCLZIP_OPT_BY_NAME, $find_file);

        $resource = $this->zip->add($file_crack,
            PCLZIP_OPT_ADD_PATH, 'encode/microedition/io',
            PCLZIP_OPT_REMOVE_ALL_PATH);

        if ($resource) {
            return true;
        }
        return false;
    }

    /**
     * @param $file
     */
    private function set_found_sms($file)
    {

        $this->file_found_sms[] = $file;
    }


    /**
     * This function will be auto add bookmark to java
     *
     * @return bool
     */
    public function setBookmark()
    {

        $check_find = false;

        $ins_file = __SITE_PATH . "/files/systems/java/bookmark/MobileAds.class";

        $find_file = "MobileAds.class";

        $this->zip->delete(PCLZIP_OPT_BY_NAME, $find_file);

        $resource = $this->zip->add($ins_file, PCLZIP_OPT_REMOVE_ALL_PATH);

        if (!$resource) {
            return false;
        }

        /**
         * get content file mf
         */
        $mf_content = $this->getFileContent($this->mf_file);

        $list_line = $this->getListLine($mf_content);

        foreach ($list_line as $key => $line) {

            if (preg_match('/MIDlet-2(.+)MobileAds/', $line)) {

                $list_line[$key] = 'MIDlet-2: ' . get_config('title_bookmark_java') . ',/zen_icon.png,MobileAds';
                $check_find = true;

            }
        }

        /**
         * if MIDlet-2 is not found
         * then add it to end file
         */
        if ($check_find == false) {
            $list_line[] = 'MIDlet-2: ' . get_config('title_bookmark_java') . ',/zen_icon.png,MobileAds';
        }

        /**
         * create new content
         */
        $new_content = implode("\r\n", $list_line);

        if ($this->addFromString($this->mf_file, $new_content)) {
            return true;
        }

        return false;
    }


    /**
     * trans \n, \r to \r\n
     * remove empty line
     *
     * @param $content
     * @return array
     */
    private function getListLine($content)
    {

        $new_content = preg_replace('/[\n\r]+/', "\r\n", $content);

        $list_line = explode("\r\n", $new_content);

        foreach ($list_line as $k => $line) {
            if (empty($line)) {
                unset($list_line[$k]);
            }
        }
        return $list_line;
    }

    /**
     * get file content from zip
     *
     * @param $file
     * @return bool
     */
    private function getFileContent($file)
    {

        if (empty($this->file) || empty($this->zip)) {
            return false;
        }

        $get = $this->zip->extract(PCLZIP_OPT_BY_NAME, $file,
            PCLZIP_OPT_EXTRACT_AS_STRING);
        return $get[0]['content'];
    }

    /**
     * save file content in zip
     *
     * @param $file
     * @param string $content
     * @return bool
     */
    private function addFromString($file, $content = '')
    {

        if (empty($this->file) || empty($this->zip)) {
            return false;
        }

        if ($this->zip->delete(PCLZIP_OPT_BY_NAME, $file)) {

            /**
             * add new file mf as new content
             */
            $out = $this->zip->add(array(array(PCLZIP_ATT_FILE_NAME => $file,
                PCLZIP_ATT_FILE_CONTENT => $content)));
            if ($out) {
                return true;
            }
            return false;
        }
    }

}

?>