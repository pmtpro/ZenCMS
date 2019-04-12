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

    public function __construct()
    {

        $this->magic_quotes_active = get_magic_quotes_gpc();

        $this->real_escape_string_exists = function_exists("mysql_real_escape_string");
    }

    /**
     * @return ZenDatabase
     */
    public static function getInstance()
    {

        if (!self::$instance) {
            $db_con = new ZenDatabase();

            if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_NAME') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD')) {

                $db_con->connect(__ZEN_DB_HOST, __ZEN_DB_USER, __ZEN_DB_PASSWORD, __ZEN_DB_NAME);
            } else {
                $db_con->connect();
            }
            self::$instance = $db_con;
        }
        return self::$instance;
    }

    /**
     * open connect
     *
     * @param string $address
     * @param string $account
     * @param string $pwd
     * @param string $name
     * @param bool $show_error
     * @return bool
     */
    function connect($address = '', $account = '', $pwd = '', $name = '', $show_error = true)
    {

        $this->connection = @mysql_connect($address, $account, $pwd);

        if (!$this->connection) {

            $this->error_msg_name = "Database connection failed";

            $this->error_msg_content = mysql_error();

            if ($show_error) {

                $this->sql_query_error($this->error_msg_name, '<div class="mysql_error">' . $this->error_msg_content . '</div>');

            }

            return false;

        } else {

            $db_select = @mysql_select_db($name, $this->connection);

            if (!$db_select) {

                $this->error_msg_name = "Database selection failed";

                $this->error_msg_content = mysql_error();

                if ($show_error) {

                    $this->sql_query_error($this->error_msg, '<div class="mysql_error">' . $this->error_msg_content . '</div>');
                }

                return false;
            }

            mysql_query("SET NAMES 'utf8'", $this->connection);

            return true;
        }
    }

    /**
     * close connected
     */
    public function closeConnect()
    {

        if ($this->connection) {

            mysql_close($this->connection);

            unset($this->connection);
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function sqlaQuote($value)
    {
        //Kiểm tra xem version PHP bạn sử dụng có hiểu hàm mysql_real_escape_string() hay ko
        if ($this->real_escape_string_exists) {
            //Trường hợp sử dụng PHP v4.3.0 trở lên
            //PHP hiểu hàm mysql_real_escape_string()

            if (get_magic_quotes_gpc()) {
                //Trong trường hợp PHP đã hỗ trợ hàm get_magic_quotes_gpc()
                //Ta sử dụng hàm stripslashes để bỏ qua các dấu slashes
                $value = @stripslashes($value);
            }
            $value = @mysql_real_escape_string($value);
        } else {
            //Trường hợp dùng cho các version PHP dưới 4.3.0
            //PHP không hiểu hàm mysql_real_escape_string()

            if (!get_magic_quotes_gpc()) {
                //Trong trường hợp PHP không hỗ trợ hàm get_magic_quotes_gpc()
                //Ta sử dụng hàm addslashes để thêm các dấu slashes vào giá trị
                $value = @addslashes($value);
            }

            // Nếu hàm get_magic_quotes_gpc() đã active có nghĩa là các dấu slashes đã tồn tại rồi
        }
        return $value;
    }

    /**
     * @param $values
     * @return array|string
     */
    public function sqlQuote($values)
    {

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

    public function sqlaQuoteRm($value)
    {
        $value = @stripslashes($value);

        return $value;
    }

    /**
     * remove char quote
     *
     * @param $values
     * @return array|string
     */
    public function sqlQuoteRm($values)
    {

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
     * @return resource
     */
    public function query($sql, $show_error = true)
    {

        $this->result = mysql_query($sql, $this->connection);

        if ($show_error) {

            if (!$this->result) {

                $erro = debug_backtrace();

                $this->sql_query_error('Database query failed', ' - The query is: <br/>
            <code>' . $sql . '</code><br/>
            <div class="mysql_error">' . mysql_error() . '</div>
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
    public function fetch_array(&$res = false)
    {
        $rows = mysql_fetch_array($res, MYSQL_ASSOC);

        return $rows;
    }

    /**
     * Count the number of records in the database via a query
     *
     * @param bool $res
     * @return int
     */
    public function num_row(&$res = false)
    {

        $num = null;
        $num = mysql_num_rows($res);
        return $num;
    }

    public function num_rows(&$res = false)
    {

        return $this->num_row($res);
    }

    /**
     * @param bool $src
     * @param int $start
     * @return string
     */
    public function result(&$src = false, $start = 0)
    {
        $num = null;
        $num = mysql_result($src, $start);
        return $num;
    }

    /**
     * @param bool $res
     * @return object|stdClass
     */
    public function fetch_object($res = false)
    {
        $rows = mysql_fetch_object($res);
        return $rows;
    }

    public function fetch_assoc(&$res = false)
    {

        $rows = mysql_fetch_assoc($res);
        return $rows;

    }

    /**
     * get last insert id
     *
     * @return int
     */
    public function insert_id()
    {
        return mysql_insert_id();
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
     *
     * @access    public
     * @param    string    the table name
     * @param    array    the update data
     * @param    array    the where clause
     * @param    array    the orderby clause
     * @param    array    the limit clause
     * @return    string
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

    private function sql_query_error($name_error = '', $msg = '')
    {

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

