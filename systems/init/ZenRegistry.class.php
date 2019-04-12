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

Class ZenRegistry
{
    /*
     * @the vars array
     * @access private
     */

    private $vars = array();

    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    /**
     *
     * @get variables
     *
     * @param mixed $index
     *
     * @return mixed
     *
     */
    public function __get($index)
    {
        return $this->vars[$index];
    }

}

?>
