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

Class validation
{

    /**
     * function to check the validity of the given string
     * $what = what you are checking (phone, email, etc)
     * $data = the string you want to check
      */
    function isValid($what, $data)
    {
        switch ($what) {

            // validate a phone number
            case 'phone':
                $pattern = "/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i";
                break;

            // validate email address
            case 'email':
                if (function_exists('filter_var')) {
                    return (filter_var($data, FILTER_VALIDATE_EMAIL)) ? true : false;
                }
                $pattern = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i";
                break;

            case 'url':

                if (function_exists('filter_var')) {
                    return (filter_var($data, FILTER_VALIDATE_URL)) ? true : false;
                }
                $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
                break;

            case 'username':
                $pattern = "/^[0-9a-zA-Z\-_\.]+$/i";
                break;

            case 'birthday':

                if (!is_numeric($data)) {
                    return false;
                }
                if ($data > 31 || $data <= 0) {
                    return false;
                }
                return true;

            case 'birthmonth':

                if (!is_numeric($data)) {
                    return false;
                }
                if ($data > 12 || $data <= 0) {
                    return false;
                }
                return true;

            case 'birthyear':

                if (!is_numeric($data)) {
                    return false;
                }
                if ($data < 1950 || $data > 2013) {
                    return false;
                }
                return true;

            case 'nameDir':
                if (strpos($data,"\x00")===false) {
                    return true;
                }
                return false;
                break;

            case 'nameFile':
                if (strpos($data,"\x00")===false) {
                    return true;
                }
                return false;
                break;

            default:
                return false;
                break;
        }
        return preg_match($pattern, $data) ? true : false;
    }

    public function url_exists($url)
    {
        $url_data = parse_url($url); // scheme, host, port, path, query
        if (!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)) {
            return FALSE;
        }
        return TRUE;
    }

}

?>