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

class ZenInput
{
    /**
     * IP address of the current user
     *
     * @var string
     */
    static $ip_address				= FALSE;
    /**
     * user agent (web browser) being used by the current user
     *
     * @var string
     */
    static $user_agent				= FALSE;

    /**
     * If TRUE, then newlines are standardized
     *
     * @var bool
     */
    static $_standardize_newlines	= TRUE;
    /**
     * Determines whether the XSS filter is always active when GET, POST or COOKIE data is encountered
     * Set automatically based on config setting
     *
     * @var bool
     */
    static $_enable_clean_xss			= FALSE;

    /**
     * List of all HTTP request headers
     *
     * @var array
     */
    static $headers			= array();

    /**
     * Security object
     */
    static $security;

    /**
     *  Constructor
     * Sets whether to globally enable the XSS processing
     * and whether to allow the $_GET array
     */
    static function init($config = array())
    {
        global $registry;
        self::$_enable_clean_xss		= empty($config['enable_clean_xss']) ? false : true;
        self::$_standardize_newlines    = (isset($config['standardize_newlines']) && !$config['standardize_newlines']) ? false : true;
        self::$security = $registry->security;

        // Sanitize global arrays
        self::_sanitize_globals();
    }

    // --------------------------------------------------------------------

    /**
     * Fetch from array
     *
     * This is a helper function to retrieve values from global arrays
     *
     * @access	private
     * @param	array
     * @param	string
     * @param	bool
     * @return	string
     */
    static function _fetch_from_array(&$array, $index = '', $cleanXSS = FALSE) {
        if (!isset($array[$index])) {
            return FALSE;
        }
        if ($cleanXSS === TRUE) {
            return self::$security->cleanXSS($array[$index]);
        }
        return $array[$index];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the GET array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    static function get($index = NULL, $cleanXSS = FALSE)
    {
        // Check if a field has been provided
        if ($index === NULL AND ! empty($_GET)) {
            $get = array();

            // loop through the full _GET array
            foreach (array_keys($_GET) as $key) {
                $get[$key] = self::_fetch_from_array($_GET, $key, $cleanXSS);
            }
            return $get;
        }

        return self::_fetch_from_array($_GET, $index, $cleanXSS);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the POST array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    static function post($index = NULL, $cleanXSS = FALSE)
    {
        // Check if a field has been provided
        if ($index === NULL && !empty($_POST)) {
            $post = array();

            // Loop through the full _POST array and return it
            foreach (array_keys($_POST) as $key) {
                $post[$key] = self::_fetch_from_array($_POST, $key, $cleanXSS);
            }
            return $post;
        }

        return self::_fetch_from_array($_POST, $index, $cleanXSS);
    }


    // --------------------------------------------------------------------

    /**
     * Fetch an item from either the GET array or the POST
     *
     * @access	public
     * @param	string	The index key
     * @param	bool	XSS cleaning
     * @return	string
     */
    static function get_post($index = '', $cleanXSS = FALSE)
    {
        if ( ! isset($_POST[$index])) {
            return self::get($index, $cleanXSS);
        } else {
            return self::post($index, $cleanXSS);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the COOKIE array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    static function cookie($index = '', $cleanXSS = FALSE)
    {
        return self::_fetch_from_array($_COOKIE, $index, $cleanXSS);
    }

    // ------------------------------------------------------------------------

    /**
     * Set cookie
     *
     * Accepts six parameter, or you can submit an associative
     * array in the first parameter containing all the values.
     *
     * @access	public
     * @param	mixed
     * @param	string	the value of the cookie
     * @param	string	the number of seconds until expiration
     * @param	string	the cookie domain.  Usually:  .yourdomain.com
     * @param	string	the cookie path
     * @param	string	the cookie prefix
     * @param	bool	true makes the cookie secure
     * @return	void
     */
    static function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
    {
        if (is_array($name)) {
            // always leave 'name' in last place, as the loop will break otherwise, due to $$item
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'name') as $item) {
                if (isset($name[$item])) {
                    $$item = $name[$item];
                }
            }
        }

        if (!$prefix && sysConfig('cookie_prefix')) {
            $prefix = sysConfig('cookie_prefix');
        }
        if ($domain == '' && sysConfig('cookie_domain')) {
            $domain = sysConfig('cookie_domain');
        }
        if ($path == '/' && sysConfig('cookie_path') && sysConfig('cookie_path') != '/') {
            $path = sysConfig('cookie_path');
        }
        if ($secure == FALSE && sysConfig('cookie_secure') != FALSE) {
            $secure = sysConfig('cookie_secure');
        }

        if ( ! is_numeric($expire)) {
            $expire = time() - 86500;
        } else {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }
        setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the SERVER array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    static function server($index = '', $xss_clean = FALSE)
    {
        return self::_fetch_from_array($_SERVER, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the IP Address
     *
     * @return	string
     */
    static function ip_address()
    {
        if (self::$ip_address !== FALSE) {
            return self::$ip_address;
        }

        $proxy_ips = sysConfig('proxy_ips');
        if ( ! empty($proxy_ips)) {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header) {
                if (($spoof = self::server($header)) !== FALSE)  {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    if (strpos($spoof, ',') !== FALSE) {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }

                    if ( ! self::valid_ip($spoof)) {
                        $spoof = FALSE;
                    } else {
                        break;
                    }
                }
            }

            self::$ip_address = ($spoof !== FALSE && in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, TRUE)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        } else {
            self::$ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if (!self::valid_ip(self::$ip_address)) {
            self::$ip_address = '0.0.0.0';
        }

        return self::$ip_address;
    }

    // --------------------------------------------------------------------

    /**
     * Validate IP Address
     *
     * @access	public
     * @param	string
     * @param	string	ipv4 or ipv6
     * @return	bool
     */
    static function valid_ip($ip, $which = '')
    {
        $which = strtolower($which);

        // First check if filter_var is available
        if (is_callable('filter_var')) {
            switch ($which) {
                case 'ipv4':
                    $flag = FILTER_FLAG_IPV4;
                    break;
                case 'ipv6':
                    $flag = FILTER_FLAG_IPV6;
                    break;
                default:
                    $flag = '';
                    break;
            }

            return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
        }

        if ($which !== 'ipv6' && $which !== 'ipv4') {
            if (strpos($ip, ':') !== FALSE) {
                $which = 'ipv6';
            } elseif (strpos($ip, '.') !== FALSE) {
                $which = 'ipv4';
            } else {
                return FALSE;
            }
        }

        $func = '_valid_'.$which;
        return self::$func($ip);
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv4 Address
     *
     * Updated version suggested by Geert De Deckere
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    static function _valid_ipv4($ip)
    {
        $ip_segments = explode('.', $ip);

        // Always 4 segments needed
        if (count($ip_segments) !== 4) {
            return FALSE;
        }
        // IP can not start with 0
        if ($ip_segments[0][0] == '0') {
            return FALSE;
        }

        // Check each segment
        foreach ($ip_segments as $segment) {
            // IP segments must be digits and can not be
            // longer than 3 digits or greater then 255
            if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3) {
                return FALSE;
            }
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv6 Address
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    static function _valid_ipv6($str)
    {
        // 8 groups, separated by :
        // 0-ffff per group
        // one set of consecutive 0 groups can be collapsed to ::

        $groups = 8;
        $collapsed = FALSE;

        $chunks = array_filter(
            preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
        );

        // Rule out easy nonsense
        if (current($chunks) == ':' OR end($chunks) == ':') {
            return FALSE;
        }

        // PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
        if (strpos(end($chunks), '.') !== FALSE) {
            $ipv4 = array_pop($chunks);

            if ( ! self::_valid_ipv4($ipv4)) {
                return FALSE;
            }

            $groups--;
        }

        while ($seg = array_pop($chunks)) {
            if ($seg[0] == ':') {
                if (--$groups == 0) {
                    return FALSE;	// too many groups
                }

                if (strlen($seg) > 2) {
                    return FALSE;	// long separator
                }

                if ($seg == '::') {
                    if ($collapsed) {
                        return FALSE;	// multiple collapsed
                    }

                    $collapsed = TRUE;
                }
            } elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4) {
                return FALSE; // invalid segment
            }
        }

        return $collapsed OR $groups == 1;
    }

    // --------------------------------------------------------------------

    /**
     * User Agent
     *
     * @access	public
     * @return	string
     */
    static function user_agent()
    {
        if (self::$user_agent !== FALSE) {
            return self::$user_agent;
        }

        self::$user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];

        return self::$user_agent;
    }

    // --------------------------------------------------------------------

    /**
     * Sanitize Globals
     *
     * This function does the following:
     *
     * Unsets $_GET data (if query strings are not enabled)
     *
     * Unsets all globals if register_globals is enabled
     *
     * Standardizes newline characters to \n
     *
     * @access	private
     * @return	void
     */
    static function _sanitize_globals()
    {
        // It would be "wrong" to unset any of these GLOBALS.
        $protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST',
            '_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
            'system_folder', 'application_folder', 'BM', 'EXT',
            'CFG', 'URI', 'RTR', 'OUT', 'IN');

        // Unset globals for securiy.
        // This is effectively the same as register_globals = off
        foreach (array($_GET, $_POST, $_COOKIE) as $global) {
            if ( ! is_array($global)) {
                if ( ! in_array($global, $protected)) {
                    global $$global;
                    $$global = NULL;
                }
            } else {
                foreach ($global as $key => $val) {
                    if ( ! in_array($key, $protected)) {
                        global $$key;
                        $$key = NULL;
                    }
                }
            }
        }

        if (is_array($_GET) AND count($_GET) > 0) {
            foreach ($_GET as $key => $val) {
                $_GET[self::_clean_input_keys($key)] = self::_clean_input_data($val);
            }
        }

        // Clean $_POST Data
        if (is_array($_POST) AND count($_POST) > 0) {
            foreach ($_POST as $key => $val) {
                $_POST[self::_clean_input_keys($key)] = self::_clean_input_data($val);
            }
        }

        // Clean $_COOKIE Data
        if (is_array($_COOKIE) AND count($_COOKIE) > 0) {
            // Also get rid of specially treated cookies that might be set by a server
            // or silly application, that are of no use to a CI application anyway
            // but that when present will trip our 'Disallowed Key Characters' alarm
            // http://www.ietf.org/rfc/rfc2109.txt
            // note that the key names below are single quoted strings, and are not PHP variables
            unset($_COOKIE['$Version']);
            unset($_COOKIE['$Path']);
            unset($_COOKIE['$Domain']);

            // Work-around for PHP bug #66827 (https://bugs.php.net/bug.php?id=66827)
            //
            // The session ID sanitizer doesn't check for the value type and blindly does
            // an implicit cast to string, which triggers an 'Array to string' E_NOTICE.
            $sess_cookie_name = sysConfig('cookie_prefix').sysConfig('sess_cookie_name');
            if (isset($_COOKIE[$sess_cookie_name]) && ! is_string($_COOKIE[$sess_cookie_name])) {
                unset($_COOKIE[$sess_cookie_name]);
            }

            foreach ($_COOKIE as $key => $val) {
                // _clean_input_data() has been reported to break encrypted cookies
                if ($key === $sess_cookie_name && sysConfig('sess_encrypt_cookie')) {
                    continue;
                }

                $_COOKIE[self::_clean_input_keys($key)] = self::_clean_input_data($val);
            }
        }

        // Sanitize PHP_SELF
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
    }

    // --------------------------------------------------------------------

    /**
     * Clean Input Data
     *
     * This is a helper function. It escapes data and
     * standardizes newline characters to \n
     *
     * @access	private
     * @param	string
     * @return	string
     */
    static function _clean_input_data($str)
    {
        if (is_array($str)) {
            $new_array = array();
            foreach ($str as $key => $val) {
                $new_array[self::_clean_input_keys($key)] = self::_clean_input_data($val);
            }
            return $new_array;
        }

        /* We strip slashes if magic quotes is on to keep things consistent

           NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
             it will probably not exist in future versions at all.
        */
        if ( ! is_php('5.4') && get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }

        // Remove control characters
        $str = remove_invisible_characters($str);

        // Should we filter the input data?
        if (self::$_enable_clean_xss === TRUE) {
            $str = self::$security->cleanXSS($str);
        }

        // Standardize newlines if needed
        if (self::$_standardize_newlines == TRUE) {
            if (strpos($str, "\r") !== FALSE) {
                $str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
            }
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Clean Keys
     *
     * This is a helper function. To prevent malicious users
     * from trying to exploit keys we make sure that keys are
     * only named with alpha-numeric text and a few other items.
     *
     * @access	private
     * @param	string
     * @return	string
     */
    static function _clean_input_keys($str)
    {
        if (!preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
            exit('Disallowed Key Characters.');
        }
        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Request Headers
     *
     * In Apache, you can simply call apache_request_headers(), however for
     * people running other webservers the function is undefined.
     *
     * @param	bool XSS cleaning
     *
     * @return array
     */
    static function request_headers($cleanXSS = FALSE)
    {
        // Look at Apache go!
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers['Content-Type'] = (isset($_SERVER['CONTENT_TYPE'])) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE');

            foreach ($_SERVER as $key => $val) {
                if (strncmp($key, 'HTTP_', 5) === 0) {
                    $headers[substr($key, 5)] = self::_fetch_from_array($_SERVER, $key, $cleanXSS);
                }
            }
        }

        // take SOME_HEADER and turn it into Some-Header
        foreach ($headers as $key => $val) {
            $key = str_replace('_', ' ', strtolower($key));
            $key = str_replace(' ', '-', ucwords($key));

            self::$headers[$key] = $val;
        }

        return self::$headers;
    }

    // --------------------------------------------------------------------

    /**
     * Get Request Header
     *
     * Returns the value of a single member of the headers class member
     *
     * @param 	string		array key for $this->headers
     * @param	boolean		XSS Clean or not
     * @return 	mixed		FALSE on failure, string on success
     */
    public function get_request_header($index, $cleanXSS = FALSE)
    {
        if (empty(self::$headers)) {
            self::request_headers();
        }

        if (!isset(self::$headers[$index])) {
            return FALSE;
        }

        if ($cleanXSS === TRUE) {
            return self::$security->cleanXSS(self::$headers[$index]);
        }

        return self::$headers[$index];
    }

    // --------------------------------------------------------------------

    /**
     * Is ajax Request?
     *
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header
     *
     * @return 	boolean
     */
    public function is_ajax_request()
    {
        return (self::server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
    }

    // --------------------------------------------------------------------

    /**
     * Is cli Request?
     *
     * Test to see if a request was made from the command line
     *
     * @return 	bool
     */
    public function is_cli_request()
    {
        return (php_sapi_name() === 'cli' OR defined('STDIN'));
    }
}