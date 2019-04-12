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

Class ZenTemplate
{
    public $template = '';
    public $template_work_name;
    public $template_work_path;
    public $isTempSystem = false;
    public static $instance;
    public static $registry;
    public $template_work_config = array();
    public $template_config = array();

    function __construct($registry) {
        self::$registry = $registry;
    }

    /**
     * @param object $registry
     * @return object
     */
    public static function getInstance($registry) {
        self::$registry = $registry;
        if (!self::$instance) {
            self::$instance = new ZenTemplate($registry);
        }
        return self::$instance;
    }

    public function setTempDir() {}

    /**
     *
     * @param string $temp
     */
    public function setTemp($temp) {
        $this->template = $temp;
    }

    /**
     * Load template
     */
    public function loader()
    {
        global $registry, $template_config;
        $registry = self::$registry;
        if (isset($this->template)) {
            $this->isTempSystem = false;

            if (strpos($this->template, ':')) {
                $hash = explode(':', $this->template);
                if ($hash[0] == 'sys') {
                    $tempName = $hash[1];
                    $this->template = $tempName;
                    $this->isTempSystem = true;
                } else $tempName = $this->template;
            } else $tempName = $this->template;

            if ($this->isTempSystem == true) {
                $baseTemp = __FILES_PATH . '/systems/templates';
            } else {
                $baseTemp = __TEMPLATES_PATH;
            }

            $temp_path_config = $baseTemp . '/' . $tempName . '/config.php';
            $temp_path_run = $baseTemp . '/' . $tempName . '/run.php';

            if (file_exists($temp_path_config) && is_readable($temp_path_config)) {

                /**
                 * include the config template file
                 */
                include_once $temp_path_config;

                $this->template_config = $template_config;
            }

            if (file_exists($temp_path_run) && is_readable($temp_path_run)) {
                /**
                 * include the run file
                 */
                include_once $temp_path_run;
            }
        } else {
            show_error(1000);
        }
    }

    public function getMap($name = '') {
        $config = $this->getTempConfig();
        if (empty($name)) {
            return $config['map'];
        } else {
            return $config['map'][$name];
        }
    }
    public function getTempConfig() {
        return $this->template_config;
    }
    public function isTempSystem() {
        return $this->isTempSystem;
    }
    public function getTemplateName() {
        return $this->template;
    }
    public function reLoader() {
        $this->loader($this->template_work_name, $this->isTempSystem);
    }
}