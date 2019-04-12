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

class BBCode
{
    private static $simple_search;
    private static $simple_replace;
    public $title = '';

    private static $lineBreaks_search = array(
        // [list]
        //'/\<br \/\>\s*\[list(.*?)\]/i',     // uncomment to remove <br /> before the tag
        '/\[list(.*?)\](.+?)\[\/list\]/sie',
        '/\[\/list\]\s*\<br \/\>/i',

        // [quote]
        //'/\<br \/\>\s*\[quote(.*?)\]/i',    // uncomment to remove <br /> before the tag
        '/\[\/quote\]\s*\<br \/\>/i',

        // [p]
        //'/\<br \/\>\s*\[p\]/i',             // uncomment to remove <br /> before the tag
        '/\[\/p\]\s*\<br \/\>/i',

        // [center]
        //'/\<br \/\>\s*\[center\]/i',        // uncomment to remove <br /> before the tag
        '/\[\/center\]\s*\<br \/\>/i',

        // [align]
        //'/\<br \/\>\s*\[align(.*?)\]/i',    // uncomment to remove <br /> before the tag
        '/\[\/align\]\s*\<br \/\>/i',

        '/\[textarea\](.+?)\[\/textarea\]/sie',

        '/\[youtube\].*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([a-zA-Z0-9_\-\+\.]*).*\[\/youtube\]/is',

    );

    private static $lineBreaks_replace = array(
        // [list]
        //"\n[list$1]",         // uncomment to remove <br /> before the tag
        "'[list$1]'.str_replace('<br />', '', '$2').'[/list]'",
        "[/list]",

        // [quote]
        //"\n[quote$1]",        // uncomment to remove <br /> before the tag
        "[/quote]",

        // [p]
        //"\n[p]",              // uncomment to remove <br /> before the tag
        "[/p]",

        // [center]
        //"\n[center]",         // uncomment to remove <br /> before the tag
        "[/center]",

        // [align]
        //"\n[align$1]",        // uncomment to remove <br /> before the tag
        "[/align]",

        "'[textarea]'.str_replace('<br />', '', '$1').'[/textarea]'",

        "[youtube]$2[/youtube]",
    );

    function __construct() {

        $home = str_replace('/', '\/', HOME);

        self::$simple_search = array(
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[s\](.*?)\[\/s\]/is',
            '/\[size\=(.*?)\](.*?)\[\/size\]/is',
            '/\[color\=(.*?)\](.*?)\[\/color\]/is',

            '/\[center\](.*?)\[\/center\]/is',
            '/\[left\](.*?)\[\/left\]/is',
            '/\[right\](.*?)\[\/right\]/is',

            '/\[font\=(.*?)\](.*?)\[\/font\]/is',
            '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',

            "/\[url\=".$home."([a-z0-9\.,_\/~#:&\=;%+\?-]*?)([\s]*)\](.*?)\[\/url\]/is",
            "/\[url\=".$home."([a-z0-9\.,_\/~#:&\=;%+\?-]*)([\s]*)title=(.*?)\](.*?)\[\/url\]/is",
            "/\[url\=".$home."([a-z0-9\.,_\/~#:&\=;%+\?-]*)([\s]*)title=(.*?)\](.*?)\[\/url\]/is",
            '/\[url\](.*?)\[\/url\]/is',
            '/\[url\=(.*?)\](.*?)\[\/url\]/is',
            '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',
            '/\[mail\](.*?)\[\/mail\]/is',
            '/\[img\](.*?)\[\/img\]/is',
            '/\[img\=(\d*?)x(\d*?)\](.*?)\[\/img\]/is',
            '/\[img (.*?)\](.*?)\[\/img\]/ise',

            '/\[code\](.*?)\[\/code\]/is',

            '/\[quote\](.*?)\[\/quote\]/is',
            '/\[quote\=(.*?)\](.*?)\[\/quote\]/is',
            "/\[c align\=(.*?)\](.*?)\[\/c\]/is",
            "/\[c\](.*?)\[\/c\]/is",


            '/\[sub\](.*?)\[\/sub\]/is',
            '/\[sup\](.*?)\[\/sup\]/is',
            '/\[p\](.*?)\[\/p\]/is',


            "/\[youtube\](.*?)\[\/youtube\]/i",
            "/\[gvideo\](.*?)\[\/gvideo\]/i",

            "/\[red\](.*?)\[\/red\]/is",
            "/\[blue\](.*?)\[\/blue\]/is",

            // "Specials", XHTML-like BBC Repository
            '/\[bull \/\]/i',
            '/\[copyright \/\]/i',
            '/\[registered \/\]/i',
            '/\[tm \/\]/i',

            '/\[textarea\](.*?)\[\/textarea\]/is',

            '/\[h1\](.*?)\[\/h1\]/is',
            '/\[h2\](.*?)\[\/h2\]/is',
            '/\[h3\](.*?)\[\/h3\]/is',
            '/\[h4\](.*?)\[\/h4\]/is',
            '/\[h5\](.*?)\[\/h5\]/is',
            '/\[h6\](.*?)\[\/h6\]/is',
        );

        self::$simple_replace = array(
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>', // you can also use: '<span style="text-decoration: underline;">$1</span>'
            '<del>$1</del>', // you can also use: '<span style="text-decoration: line-through;">$1</span>'
            '<span style="font-size: $1;">$2</span>',
            '<span style="color: $1;">$2</span>',

            '<div style="text-align: center;">$1</div>',
            '<div style="text-align: left;">$1</div>',
            '<div style="text-align: right;">$1</div>',

            '<span style="font-family: $1;">$2</span>',
            '<div style="text-align: $1;">$2</div>',

            '<a href="'.HOME.'$1" title="'.$this->title.'" rel="dofollow">$3</a>',
            '<a href="'.HOME.'$1" title="$3" rel="dofollow">$4</a>',
            '<a href="'.HOME.'$1" title="$3" rel="dofollow">$4</a>',
            '<a href="$1" target="_blank">$1</a>',
            '<a href="$1" target="_blank">$2</a>',
            '<a href="mailto:$1">$2</a>',
            '<a href="mailto:$1">$1</a>',
            '<img src="$1" alt="" />',
            '<img height="$2" width="$1" alt="" src="$3" />',
            '"<img " . str_replace("&#039;", "\"",str_replace("&quot;", "\"", "$1")) . " src=\"$2\" />"', // we replace &quot; and &#039; to " in case if we got string converted to html entities

            '<span class="codeStyle">$1</span>',

            '<span class="quoteStyle">$1</span>', // you can also use: '<div class="quote">$1</div>'
            '<span class="quoteStyle"><strong>$1:</strong><br/> $2</span>', // you can also use: '<div class="quote"><strong>$1 wrote:</strong> $2</div>
            '<span class="quote" style="display:block;text-align:$1">$2</span>',
            '<span class="quote" style="display:block;">$1</span>',


            '<sub>$1</sub>',
            '<sup>$1</sup>',
            '<p>$1</p>',


            "<iframe width=\"500\" height=\"281\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",
            "<embed src=\"http://video.google.com/googleplayer.swf?docId=$1\" type=\"application/x-shockwave-flash\" style=\"width: 425px; height: 350px;\">",

            '<span style="color:red;">$1</span>',
            '<span style="color:blue;">$1</span>',

            // "Specials", XHTML-like BBC Repository
            '&bull;',
            '&copy;',
            '&reg;',
            '&trade;',

            '<textarea class="textarea_box">$1</textarea>',

            '<h1>$1</h1>',
            '<h2>$1</h2>',
            '<h3>$1</h3>',
            '<h4>$1</h4>',
            '<h5>$1</h5>',
            '<h6>$1</h6>',
        );
    }
    private function removeBr($str) {
        return preg_replace('/<br\s*\/?>/', "" ,$str);
    }
    private static function process_list_items($list_items)
    {
        $result_list_items = array();

        // Check for [li][/li] tags
        preg_match_all("/\[li\](.*?)\[\/li\]/is", $list_items, $li_array);
        $li_array = $li_array[1];

        if (empty($li_array)) {
            // we didn't find any [li] tags
            $list_items_array = explode("[*]", $list_items);
            foreach ($list_items_array as $li_text) {
                $li_text = trim($li_text);
                if (empty($li_text)) {
                    continue;
                }
                $li_text = nl2br($li_text);
                $result_list_items[] = '<li>' . $li_text . '</li>';
            }
        } else {
            // we found [li] tags!
            foreach ($li_array as $li_text) {
                $li_text = nl2br($li_text);
                $result_list_items[] = '<li>' . $li_text . '</li>';
            }
        }

        $list_items = implode("\n", $result_list_items);

        return $list_items;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function auto_detected_img($str = '') {

        $str = ' ' . $str . ' ';

        $seach[]='`\[img\](\s+)(\S*)(\s+)\[\/img\]`';
        $replace[]='[img]$2[/img]';

        $seach[]='`(\s+)((http|https|ftp)://[^\s<]+[^\s<\.)](\.jpg|\.jpeg|\.png|\.gif|\.bmp))(\s+)`is';
        $replace[]='$1[img]$2[/img]$5';

        $str = preg_replace($seach,$replace,$str);
        $str = substr($str, 1);

        return $str;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function auto_detected_youtube($str = '') {

        $str = ' ' . $str . ' ';

        $seach[]='`\[youtube\](\s+)(\S*)(\s+)\[\/youtube\]`';
        $replace[]='[youtube]$2[/youtube]';

        $seach[]='/(\s+)https?:\/\/\S+(embed\/|watch\?v=|\&v=)([a-zA-Z0-9_\-\+\.]*)(\s+)/is';
        $replace[]='$1[youtube]$3[/youtube]$4';

        $str = preg_replace($seach,$replace,$str);
        $str = substr($str, 1);

        return $str;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function auto_detected_link($str = '') {

        $str = ' ' . $str . ' ';

        $seach[]='`\[url=(\s+)(\S*)(\s+)\](.*)\[\/url\]`is';
        $replace[]='[url=$2]$4[/url]';

        $seach[]='`(\s+)((http|https|ftp)://[^\s<]+[^\s<\.)])(\s+)`is';
        $replace[]='$1[url=$2]$2[/url]$4';

        $str = preg_replace($seach,$replace,$str);
        $str = substr($str, 1);

        return $str;
    }

    private static function highlight_code($var)
    {
        if (!function_exists('process_code')) {
            function process_code($php)
            {
                $php = strtr($php, array('<br />' => '', '\\' => 'slash_ZEN'));
                $php = html_entity_decode(trim($php), ENT_QUOTES, 'UTF-8');
                $php = substr($php, 0, 2) != "<?" ? "<?php\n" . $php . "\n?>" : $php;
                $php = highlight_string(stripslashes($php), true);
                $php = strtr($php, array('slash_ZEN' => '&#92;', ':' => '&#58;', '[' => '&#91;'));
                return '<div class="codeStyle">' . $php . '</div>';
            }
        }
        return preg_replace(array('#\[code\](.+?)\[\/code\]#ise'), array("''.process_code('$1').''"), str_replace("]\n", "]", $var));

    }


    function strip(&$text, $uid = '')
    {
        if (!$uid)
        {
            $uid = '[0-9a-zA-Z]{5,}';
        }

        $text = preg_replace("#\[\/?[A-Za-z0-9\*\+\-]+(?:=(?:&quot;.*&quot;|[^\]]*))?(?::[a-zA-Z])?(\:$uid)\]#", ' ', $text);

        return $text;
    }


    /**
     * @param string $title
     */
    public function set_title($title = '') {
        $this->title = $title;
    }

    /*
    ARGUMENTS :
    $string = the text you want to format
    */
    /*
      It is recomended to do $string = htmlentities($string) before calling parse
    */
    public static function parse($string)
    {

        $s = (string)$string;

        if (empty($s)) {
            return '';
        }

        // Preappend http:// to url address if not present
        $s = preg_replace('/\[url\=([^(http)].+?)\](.*?)\[\/url\]/i', '[url=http://$1]$2[/url]', $s);
        $s = preg_replace('/\[url\]([^(http)].+?)\[\/url\]/i', '[url=http://$1]$1[/url]', $s);

        // Add line breaks
        $s = nl2br($s);

        $s = self::auto_detected_img($s);
        $s = self::auto_detected_youtube($s);
        $s = self::auto_detected_link($s);

        // Remove the trash made by previous
        $s = preg_replace(self::$lineBreaks_search, self::$lineBreaks_replace, $s);

        // Parse bbcode
        $s = preg_replace(self::$simple_search, self::$simple_replace, $s);

        // Parse [list] tags
        $s = preg_replace('/\[list\](.*?)\[\/list\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', $s);
        $s = preg_replace('/\[ul\](.*?)\[\/ul\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', $s);
        $s = preg_replace('/\[ol\](.*?)\[\/ol\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', $s);

        // highlight_code
        $s = self::highlight_code($s);

        $s = preg_replace('/\[list\=(disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-greek|lower-alpha|lower-latin|upper-alpha|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha|none)\](.*?)\[\/list\]/sie',
            '"<ol style=\"list-style-type: $1;\">\n".self::process_list_items("$2")."\n</ol>"', $s);

        return $s;
    }

}

?>