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

class ZenDatabase
{
    private $connection;
    private $result = null;
    private $magic_quotes_active;
    private $real_escape_string_exists;
    private static $instance;
    public $count_query = 0;
    public $count_cache = 0;
    public $error_msg_name = '';
    public $error_msg_content = '';

    public $db_host = '';
    public $db_user = '';
    public $db_pass = '';
    public $db_name = '';

    public function __construct() {
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists = function_exists("mysqli_real_escape_string");
    }

    /**
     * @return ZenDatabase
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            $db_con = new ZenDatabase();
            if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_NAME') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD')) {
                $db_con->db_host = __ZEN_DB_HOST;
                $db_con->db_user = __ZEN_DB_USER;
                $db_con->db_pass = __ZEN_DB_PASSWORD;
                $db_con->db_name = __ZEN_DB_NAME;
            }
            self::$instance = $db_con;
        }
        return self::$instance;
    }

    /**
     * @param null $db_host
     * @param $db_user
     * @param $db_pass
     * @param $db_name
     * @param bool $show_error
     * @return bool
     */
    function connect($show_error = true, $db_host = null, $db_user = null, $db_pass = null, $db_name = null) {
        if ($db_host && $db_user && $db_name) {
            $cur_db_host = $db_host;
            $cur_db_user = $db_user;
            $cur_db_pass = $db_pass;
            $cur_db_name = $db_name;
        } else {
            $cur_db_host = $this->db_host;
            $cur_db_user = $this->db_user;
            $cur_db_pass = $this->db_pass;
            $cur_db_name = $this->db_name;
        }
        $this->connection = mysqli_connect($cur_db_host, $cur_db_user, $cur_db_pass, $cur_db_name);
        if (mysqli_connect_errno() !== 0) {
            $this->error_msg_name = "Database connection failed";
            $this->error_msg_content = mysqli_connect_error();
            if ($show_error) {
                $this->sql_query_error($this->error_msg_name, '<div class="mysql_error">' . $this->error_msg_content . '</div>');
            }
            return false;
        } else {
            $this->query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
            return true;
        }
    }

    /**
     * close connected
     */
    public function closeConnect() {
        if ($this->connection) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function sqlaQuote($value)
    {
        if ($this->real_escape_string_exists) {
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            $value = mysqli_real_escape_string($this->connection, $value);

        } else {
            if (!get_magic_quotes_gpc()) {
                $value = addslashes($value);
            }
        }
        return $value;
    }

    /**
     * @param $values
     * @return array|string
     */
    public function sqlQuote($values) {
        if (is_array($values)) {
            $out = array();
            foreach ($values as $id => $val) {
                if (!is_array($val)) {
                    $out[$id] = $this->sqlaQuote($val);
                } else {
                    $out[$id] = $this->sqlQuote($val);
                }
            }
        } else {
            $out = $this->sqlaQuote($values);
        }
        return $out;
    }

    public function sqlaQuoteRm($value) {
        $value = stripslashes($value);
        return $value;
    }

    /**
     * remove char quote
     *
     * @param $values
     * @return array|string
     */
    public function sqlQuoteRm($values) {
        if (is_array($values)) {
            $out = array();
            foreach ($values as $id => $val) {
                if (!is_array($val)) {
                    $out[$id] = $this->sqlaQuoteRm($val);
                } else {
                    $out[$id] = $this->sqlQuoteRm($val);
                }
            }
        } else {
            $out = $this->sqlaQuoteRm($values);
        }
        return $out;
    }

    /**
     * @param $sql
     * @param bool $show_error
     * @return bool|mysqli_result
     */
    public function query($sql, $show_error = true)
    {
        if (!$this->connection) $this->connect($show_error);
        $this->result = mysqli_query($this->connection, $sql);
        if ($show_error) {
            if (!$this->result) {
                $erro = debug_backtrace();
                $this->sql_query_error('Database query failed', ' - The query is: <br/>
            <code>' . $sql . '</code><br/>
            <div class="mysql_error">' . mysqli_error($this->connection) . '</div>
             - Error in <b>' . $erro[1]['file'] . '</b><br/>
             - On line <b>' . $erro[1]['line'] . '</b>');
            }
        }
        return $this->result;
    }


    /**
     * Get array of data records in the database via a query
     *
     * @param bool $res
     * @return array
     */
    public function fetch_array(&$res = false) {
        $rows = mysqli_fetch_array($res, MYSQL_ASSOC);
        return $rows;
    }

    /**
     * Count the number of records in the database via a query
     *
     * @param bool $res
     * @return int
     */
    public function num_row(&$res = false) {
        $num = null;
        $num = mysqli_num_rows($res);
        return $num;
    }

    public function num_rows(&$res = false) {
        return $this->num_row($res);
    }

    /**
     * @param bool $res
     * @return object|stdClass
     */
    public function fetch_object(&$res = false) {
        $rows = mysqli_fetch_object($res);
        return $rows;
    }

    public function fetch_assoc(&$res = false) {
        $rows = mysqli_fetch_assoc($res);
        return $rows;
    }

    /**
     * get last insert id
     * @return int
     */
    public function insert_id() {
        return mysqli_insert_id($this->connection);
    }

    /**
     * Insert statement
     *
     * @access    public
     * @param    string    the table name
     * @param    array    the insert values
     * @return    string
     */
    function _sql_insert($table, $values = array())
    {
        foreach ($values as $key => $val) {
            $valstr[] = "`$key` = '$val'";
        }
        return "INSERT INTO " . $table . " SET " . implode(', ', $valstr);
    }

    /**
     * Update statement
     * @param string $table the table name
     * @param array $values the update data
     * @param array $where the where clause
     * @param array $orderby the orderby clause
     * @param bool $limit   the limit clause
     * @return string
     */
    function _sql_update($table, $values, $where = array(), $orderby = array(), $limit = FALSE)
    {
        foreach ($values as $key => $val) {
            if (preg_match('/^\{(.*)\}$/', $val)) {
                $val = preg_replace('/^\{(.*)\}$/', '$1', $val);
                $valstr[] = "`$key` = $val";
            } else {
                $valstr[] = "`$key` = '$val'";
            }
        }
        $limit = (!$limit) ? '' : ' LIMIT ' . $limit;
        $orderby = (count($orderby) >= 1) ? ' ORDER BY ' . implode(", ", $orderby) : '';
        $sql = "UPDATE " . $table . " SET " . implode(', ', $valstr);
        if (!is_array($where)) {
            $sql .= " WHERE " . $where;
        } else {
            $sql .= ($where != '' AND count($where) >= 1) ? " WHERE " . implode(" ", $where) : '';
        }
        if (!empty($limit)) {
            $select_limit = " LIMIT " . $limit;
        } else {
            $select_limit = "";
        }
        $sql .= $orderby . $select_limit;
        return $sql;
    }

    function __destruct() {
        $this->closeConnect();
    }

    private function sql_query_error($name_error = '', $msg = '') {
        echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    body {
        margin: 0;
        font: 14px Verdana, Geneva, Arial, Helvetica, sans-serif;
        font-size: 13px;
        line-height: 20px;
        color: #220F03;
    }
    .sql_query_error {
        padding: 20px;
        color: #D8000C;
        background-color: #FFBABA;
        border-radius: 20px;
        margin: 20px;
        border: 1px solid #F69797;
    }
    .mysql_error {
        padding: 10px;
        color: #740B0B;
    }
    code {
        padding: 10px;
    }
    </style>
    <title>' . $name_error . '</title>
    </head>
    <body>
        <div id="body">
            <div class="sql_query_error">
                <h1>ZenCMS</h1>
                <h2>' . $name_error . '</h2>
                ' . $msg . '
            </div>
        </div>
    </body>
    </html>';
        exit;
    }
}

