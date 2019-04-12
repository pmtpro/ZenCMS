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

Class ZenSmarty
{
    protected $file;
    protected $input;
    protected $inContent;
    protected $outContent;
    protected $values = array();
    protected $valuesReplace = array();

    // tags registered by the developers
    protected static $registered_tags = array();

    // tags natively supported
    protected static $tags = array(
        'loop' => array(
            '({loop.*?})',
            '/{loop="(?<variable>\${0,1}[^"]*)"(?: as (?<key>\$.*?)(?: => (?<value>\$.*?)){0,1}){0,1}}/'
        ),
        'loop_close' => array('({\/loop})', '/{\/loop}/'),
        'loop_break' => array('({break})', '/{break}/'),
        'loop_continue' => array('({continue})', '/{continue}/'),
        'if' => array('({if.*?})', '/{if="([^"]*)"}/'),
        'elseif' => array('({elseif.*?})', '/{elseif="([^"]*)"}/'),
        'else' => array('({else})', '/{else}/'),
        'if_close' => array('({\/if})', '/{\/if}/'),
        'autoescape' => array('({autoescape.*?})', '/{autoescape="([^"]*)"}/'),
        'autoescape_close' => array('({\/autoescape})', '/{\/autoescape}/'),
        'noparse' => array('({noparse})', '/{noparse}/'),
        'noparse_close' => array('({\/noparse})', '/{\/noparse}/'),
        'ignore' => array('({ignore}|{\*)', '/{ignore}|{\*/'),
        'ignore_close' => array('({\/ignore}|\*})', '/{\/ignore}|\*}/'),
        'include' => array('({include.*?})', '/{include="([^"]*)"}/'),
        'function' => array(
            '({function.*?})',
            '/{function="([a-zA-Z_][a-zA-Z_0-9\:]*)(\(.*\)){0,1}"}/'
        ),
        'ternary' => array('({.[^{?]*?\?.*?\:.*?})', '/{(.[^{?]*?)\?(.*?)\:(.*?)}/'),
        'variable' => array('({\$.*?})', '/{(\$.*?)}/'),
        'constant' => array('({#.*?})', '/{#(.*?)#{0,1}}/'),
    );

    // black list of functions and variables
    protected static $black_list = array(
        'exec', 'shell_exec', 'pcntl_exec', 'passthru', 'proc_open', 'system',
        'posix_kill', 'posix_setsid', 'pcntl_fork', 'posix_uname', 'php_uname',
        'phpinfo', 'popen', 'file_get_contents', 'file_put_contents', 'rmdir',
        'mkdir', 'unlink', 'highlight_contents', 'symlink',
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables',
        'escapeshellarg', 'escapeshellcmd', 'eval', 'fp', 'fput',
        'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput',
        'ftp_put', 'ftp_raw', 'ftp_rawlist', 'highlight_file', 'ini_alter',
        'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'passthru', 'php_uname', 'phpAds_remoteInfo',
        'phpAds_XmlRpc', 'phpAds_xmlrpcDecode', 'phpAds_xmlrpcEncode',
        'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid',
        'posix_setsid', 'posix_setuid', 'posix_uname', 'proc_close',
        'proc_get_status', 'proc_nice', 'proc_open', 'proc_terminate',
        'syslog', 'xmlrpc_entity_decode'
    );

    public $config = array(
        'php_enabled' => false,
        'remove_comments' => true,
        'auto_escape' => false
    );

    protected $error = array();

    public function set_dir($dir)
    {
        $this->config['tpl_dir'][] = $dir;
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->values = array_merge($this->values, $key);
        } else {
            $this->values[$key] = $value;
        }
    }

    public function display($input)
    {
        echo $this->fetch($input);
    }

    public function display_text($text) {
        echo $this->fetch_text($text);
    }

    public function fetch_text($text) {
        return $this->fetch('text:' . $text);
    }

    public function fetch($input)
    {
        $this->input = $input;
        $this->process($input);
        extract($this->values);
        ob_start();
        eval('?>' . $this->outContent);
        $html = ob_get_clean();
        return $html;
    }

    protected function process()
    {
        if (strpos($this->input, 'text:') === 0) {
            $this->inContent = preg_replace('/^text:/i', '', $this->input);
        } else {
            $file = $this->file . '/' . $this->input;
            if (file_exists($file)) {
                $this->file = $file;
                $this->inContent = file_get_contents($this->file);
            } else {
                return 'Error loading file <' . $file . '>';
            }
        }
        $this->outContent = $this->compile($this->inContent);
    }

    public function compile($string)
    {
        // xml substitution
        $string = preg_replace("/<\?xml(.*?)\?>/s", /*<?*/
            "##XML\\1XML##", $string);

        // disable php tag
        if (!$this->config['php_enabled'])
            $string = str_replace(array("<?", "?>"), array("&lt;?", "?&gt;"), $string);

        // xml re-substitution
        $string = preg_replace_callback("/##XML(.*?)XML##/s", function ($match) {
            return "<?php echo '<?xml " . stripslashes($match[1]) . " ?>'; ?>";
        }, $string);

        $parsedCode = $this->compile_string($string);

        // fix the php-eating-newline-after-closing-tag-problem
        $parsedCode = str_replace("?>\n", "?>\n\n", $parsedCode);
        return $parsedCode;
    }

    protected function compile_string($string)
    {

        $tagSplit = array();
        $tagMatch = array();
        // set tags
        foreach (static::$tags as $tag => $tagArray) {
            list($split, $match) = $tagArray;
            $tagSplit[$tag] = $split;
            $tagMatch[$tag] = $match;
        }

        $keys = array_keys(static::$registered_tags);
        $tagSplit += array_merge($tagSplit, $keys);

        //Remove comments
        if ($this->config['remove_comments']) {
            $code = preg_replace('/<!--(.*)-->/Uis', '', $string);
        }

        //split the code with the tags regexp
        $codeSplit = preg_split("/" . implode("|", $tagSplit) . "/", $string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        //variables initialization
        $parsedCode = $commentIsOpen = $ignoreIsOpen = NULL;
        $openIf = $loopLevel = 0;

        // if the template is not empty
        if ($codeSplit)

            //read all parsed code
            foreach ($codeSplit as $html) {

                //close ignore tag
                if (!$commentIsOpen && preg_match($tagMatch['ignore_close'], $html))
                    $ignoreIsOpen = FALSE;

                //code between tag ignore id deleted
                elseif ($ignoreIsOpen) {
                    //ignore the code
                } //close no parse tag
                elseif (preg_match($tagMatch['noparse_close'], $html))
                    $commentIsOpen = FALSE;

                //code between tag noparse is not compiled
                elseif ($commentIsOpen)
                    $parsedCode .= $html;

                //ignore
                elseif (preg_match($tagMatch['ignore'], $html))
                    $ignoreIsOpen = TRUE;

                //noparse
                elseif (preg_match($tagMatch['noparse'], $html))
                    $commentIsOpen = TRUE;

                 //loop
                elseif (preg_match($tagMatch['loop'], $html, $matches)) {

                    // increase the loop counter
                    $loopLevel++;

                    //replace the variable in the loop
                    $var = $this->varReplace($matches['variable'], $loopLevel - 1, $escape = FALSE);
                    if (preg_match('#\(#', $var)) {
                        $newvar = "\$newvar{$loopLevel}";
                        $assignNewVar = "$newvar=$var;";
                    } else {
                        $newvar = $var;
                        $assignNewVar = null;
                    }

                    //loop variables
                    $counter = "\$counter$loopLevel"; // count iteration

                    if (isset($matches['key']) && isset($matches['value'])) {
                        $key = $matches['key'];
                        $value = $matches['value'];
                    } elseif (isset($matches['key'])) {
                        $key = "\$key$loopLevel"; // key
                        $value = $matches['key'];
                    } else {
                        $key = "\$key$loopLevel"; // key
                        $value = "\$value$loopLevel"; // value
                    }

                    //loop code
                    $parsedCode .= "<?php $counter=-1; $assignNewVar if( isset($newvar) && ( is_array($newvar) || $newvar instanceof Traversable ) && sizeof($newvar) ) foreach( $newvar as $key => $value ){ $counter++; ?>";
                } //close loop tag
                elseif (preg_match($tagMatch['loop_close'], $html)) {

                    //iterator
                    $counter = "\$counter$loopLevel";

                    //decrease the loop counter
                    $loopLevel--;

                    //close loop code
                    $parsedCode .= "<?php } ?>";
                } //break loop tag
                elseif (preg_match($tagMatch['loop_break'], $html)) {
                    //close loop code
                    $parsedCode .= "<?php break; ?>";
                } //continue loop tag
                elseif (preg_match($tagMatch['loop_continue'], $html)) {
                    //close loop code
                    $parsedCode .= "<?php continue; ?>";
                } //if
                elseif (preg_match($tagMatch['if'], $html, $matches)) {

                    //increase open if counter (for intendation)
                    $openIf++;

                    //tag
                    $tag = $matches[0];

                    //condition attribute
                    $condition = $matches[1];

                    //variable substitution into condition (no delimiter into the condition)
                    $parsedCondition = $this->varReplace($condition, $loopLevel, $escape = FALSE);

                    //if code
                    $parsedCode .= "<?php if( $parsedCondition ){ ?>";
                } //elseif
                elseif (preg_match($tagMatch['elseif'], $html, $matches)) {

                    //tag
                    $tag = $matches[0];

                    //condition attribute
                    $condition = $matches[1];

                    //variable substitution into condition (no delimiter into the condition)
                    $parsedCondition = $this->varReplace($condition, $loopLevel, $escape = FALSE);

                    //elseif code
                    $parsedCode .= "<?php }elseif( $parsedCondition ){ ?>";
                } //else
                elseif (preg_match($tagMatch['else'], $html)) {

                    //else code
                    $parsedCode .= '<?php }else{ ?>';
                } //close if tag
                elseif (preg_match($tagMatch['if_close'], $html)) {

                    //decrease if counter
                    $openIf--;

                    // close if code
                    $parsedCode .= '<?php } ?>';
                } // autoescape off
                elseif (preg_match($tagMatch['autoescape'], $html, $matches)) {

                    // get function
                    $mode = $matches[1];
                    $this->config['auto_escape_old'] = $this->config['auto_escape'];

                    if ($mode == 'off' or $mode == 'false' or $mode == '0' or $mode == null) {
                        $this->config['auto_escape'] = false;
                    } else {
                        $this->config['auto_escape'] = true;
                    }
                } // autoescape on
                elseif (preg_match($tagMatch['autoescape_close'], $html, $matches)) {
                    $this->config['auto_escape'] = $this->config['auto_escape_old'];
                    unset($this->config['auto_escape_old']);
                } // function
                elseif (preg_match($tagMatch['function'], $html, $matches)) {

                    // get function
                    $function = $matches[1];

                    // var replace
                    if (isset($matches[2]))
                        $parsedFunction = $function . $this->varReplace($matches[2], $loopLevel, $escape = FALSE, $echo = FALSE);
                    else
                        $parsedFunction = $function . "()";

                    // check black list
                    //$this->check_blackList($parsedFunction);

                    // function
                    $parsedCode .= "<?php echo $parsedFunction; ?>";
                } //ternary
                elseif (preg_match($tagMatch['ternary'], $html, $matches)) {
                    $parsedCode .= "<?php echo " . '(' . $this->varReplace($matches[1], $loopLevel, $escape = TRUE, $echo = FALSE) . '?' . $this->varReplace($matches[2], $loopLevel, $escape = TRUE, $echo = FALSE) . ':' . $this->varReplace($matches[3], $loopLevel, $escape = TRUE, $echo = FALSE) . ')' . "; ?>";
                } //variables
                elseif (preg_match($tagMatch['variable'], $html, $matches)) {
                    //variables substitution (es. {$title})
                    $parsedCode .= "<?php " . $this->varReplace($matches[1], $loopLevel, $escape = TRUE, $echo = TRUE) . "; ?>";
                } //constants
                elseif (preg_match($tagMatch['constant'], $html, $matches)) {
                    $parsedCode .= "<?php echo " . $this->conReplace($matches[1], $loopLevel) . "; ?>";
                } // registered tags
                else {

                    $found = FALSE;
                    foreach (static::$registered_tags as $tags => $array) {
                        if (preg_match_all('/' . $array['parse'] . '/', $html, $matches)) {
                            $found = true;
                            $parsedCode .= "<?php echo call_user_func( static::\$registered_tags['$tags']['function'], " . var_export($matches, 1) . " ); ?>";
                        }
                    }

                    if (!$found) {
                        $parsedCode .= $html;
                    }
                }
            }

        if ($openIf > 0) {
            $trace = debug_backtrace();
            $caller = array_shift($trace);
            $this->set_error("Error! You need to close an {if} tag in the string, loaded by {$caller['file']} at line {$caller['line']}");
        }

        if ($loopLevel > 0) {
            $trace = debug_backtrace();
            $caller = array_shift($trace);
            $this->set_error("Error! You need to close the {loop} tag in the string, loaded by {$caller['file']} at line {$caller['line']}");
        }

        $html = str_replace('?><?php', ' ', $parsedCode);
        return $parsedCode;
    }

    protected function varReplace($html, $loopLevel = NULL, $escape = TRUE, $echo = FALSE)
    {

        // change variable name if loop level
        if (!empty($loopLevel))
            $html = preg_replace(array('/(\$key)\b/', '/(\$value)\b/', '/(\$counter)\b/'), array('${1}' . $loopLevel, '${1}' . $loopLevel, '${1}' . $loopLevel), $html);

        // if it is a variable
        if (preg_match_all('/(\$[a-z_A-Z][^\s]*)/', $html, $matches)) {
            // substitute . and [] with [" "]
            for ($i = 0; $i < count($matches[1]); $i++) {

                $rep = preg_replace('/\[(\${0,1}[a-zA-Z_0-9]*)\]/', '["$1"]', $matches[1][$i]);
                //$rep = preg_replace('/\.(\${0,1}[a-zA-Z_0-9]*)/', '["$1"]', $rep);
                $rep = preg_replace('/\.(\${0,1}[a-zA-Z_0-9]*(?![a-zA-Z_0-9]*(\'|\")))/', '["$1"]', $rep);
                $html = str_replace($matches[0][$i], $rep, $html);
            }

            // update modifier
            $html = $this->modifierReplace($html);

            // if does not initialize a value, e.g. {$a = 1}
            if (!preg_match('/\$.*=.*/', $html)) {

                // escape character
                if ($this->config['auto_escape'] && $escape)
                    //$html = "htmlspecialchars( $html )";
                    $html = "htmlspecialchars( $html, ENT_COMPAT, '" . $this->config['charset'] . "', FALSE )";

                // if is an assignment it doesn't add echo
                if ($echo)
                    $html = "echo " . $html;
            }
        }
        return $html;
    }

    protected function conReplace($html) {
        return $this->modifierReplace($html);
    }

    protected function modifierReplace($html)
    {

        if (strpos($html, '|') !== false && substr($html, strpos($html, '|') + 1, 1) != "|") {
            preg_match('/([\$a-z_A-Z0-9\(\),\[\]"->]+)\|([\$a-z_A-Z0-9\(\):,\[\]"->]+)/i', $html, $result);

            $function_params = $result[1];
            $explode = explode(":", $result[2]);
            $function = $explode[0];
            $params = isset($explode[1]) ? "," . $explode[1] : null;

            $html = str_replace($result[0], $function . "(" . $function_params . "$params)", $html);

            if (strpos($html, '|') !== false && substr($html, strpos($html, '|') + 1, 1) != "|") {
                $html = $this->modifierReplace($html);
            }
        }

        return $html;
    }

    public function set_error($msg)
    {
        $this->error[] = $msg;
    }
}