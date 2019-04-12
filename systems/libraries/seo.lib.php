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

Class seo
{

    function url($str = '', $options = array())
    {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        if (!function_exists('mb_list_encodings')) {
            $list_encodings = $this->mb_list_encodings_m();
        } else {
            $list_encodings = mb_list_encodings();
        }
        $str = mb_convert_encoding((string)$str, 'UTF-8', $list_encodings);

        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => true,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);


        //replace char nokia to utf-8
        $str = $this->NokiaFixer($str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = $this->translate2en($str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

    function NokiaFixer($str = '')
    {
        $char = array('ắ' => 'ắ', 'ằ' => 'ằ', 'ẳ' => 'ẳ', 'ẵ' => 'ẵ', 'ặ' => 'ặ', 'ấ' => 'ấ', 'ầ' => 'ầ', 'ẩ' => 'ẩ',
            'ẫ' => 'ẫ', 'ậ' => 'ậ', 'ố' => 'ố', 'ồ' => 'ồ', 'ổ' => 'ổ', 'ỗ' => 'ỗ', 'ộ' => 'ộ', 'ớ' => 'ớ',
            'ờ' => 'ờ', 'ở' => 'ở', 'ỡ' => 'ỡ', 'ợ' => 'ợ', 'ứ' => 'ứ', 'ừ' => 'ừ', 'ử' => 'ử', 'ữ' => 'ữ',
            'ự' => 'ự', 'á' => 'á', 'à' => 'à', 'ả' => 'ả', 'ã' => 'ã', 'ạ' => 'ạ', 'ó' => 'ó', 'ò' => 'ò',
            'ỏ' => 'ỏ', 'õ' => 'õ', 'ọ' => 'ọ', 'é' => 'é', 'è' => 'è', 'ẻ' => 'ẻ', 'ẽ' => 'ẽ', 'ẹ' => 'ẹ',
            'ế' => 'ế', 'ề' => 'ề', 'ể' => 'ể', 'ễ' => 'ễ', 'ệ' => 'ệ', 'í' => 'í', 'ì' => 'ì', 'ỉ' => 'ỉ',
            'ĩ' => 'ĩ', 'ị' => 'ị', 'ý' => 'ý', 'ỳ' => 'ỳ', 'ỷ' => 'ỷ', 'ỹ' => 'ỹ', 'ỵ' => 'ỵ', 'ú' => 'ú',
            'ù' => 'ù', 'ủ' => 'ủ', 'ũ' => 'ũ', 'ụ' => 'ụ', 'Ắ' => 'Ắ', 'Ằ' => 'Ằ', 'Ẳ' => 'Ẳ', 'Ẵ' => 'Ẵ',
            'Ặ' => 'Ặ', 'Ấ' => 'Ấ', 'Ầ' => 'Ầ', 'Ẩ' => 'Ẩ', 'Ẫ' => 'Ẫ', 'Ậ' => 'Ậ', 'Ố' => 'Ố', 'Ồ' => 'Ồ',
            'Ổ' => 'Ổ', 'Ỗ' => 'Ỗ', 'Ộ' => 'Ộ', 'Ớ' => 'Ớ', 'Ờ' => 'Ờ', 'Ở' => 'Ở', 'Ỡ' => 'Ỡ', 'Ợ' => 'Ợ',
            'Ứ' => 'Ứ', 'Ừ' => 'Ừ', 'Ử' => 'Ử', 'Ữ' => 'Ữ', 'Ự' => 'Ự', 'Á' => 'Á', 'À' => 'À', 'Ả' => 'Ả',
            'Ã' => 'Ã', 'Ạ' => 'Ạ', 'Ó' => 'Ó', 'Ò' => 'Ò', 'Ỏ' => 'Ỏ', 'Õ' => 'Õ', 'Ọ' => 'Ọ', 'É' => 'É',
            'È' => 'È', 'Ẻ' => 'Ẻ', 'Ẽ' => 'Ẽ', 'Ẹ' => 'Ẹ', 'Ế' => 'Ế', 'Ề' => 'Ề', 'Ể' => 'Ể', 'Ễ' => 'Ễ',
            'Ệ' => 'Ệ', 'Í' => 'Í', 'Ì' => 'Ì', 'Ỉ' => 'Ỉ', 'Ĩ' => 'Ĩ', 'Ị' => 'Ị', 'Ý' => 'Ý', 'Ỳ' => 'Ỳ',
            'Ỷ' => 'Ỷ', 'Ỹ' => 'Ỹ', 'Ỵ' => 'Ỵ', 'Ú' => 'Ú', 'Ù' => 'Ù', 'Ủ' => 'Ủ', 'Ũ' => 'Ũ', 'Ụ' => 'Ụ');

        //replace char nokia to utf-8
        $str = str_replace(array_keys($char), $char, $str);

        return $str;
    }

    public function translate2en($str = '')
    {
        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            //Translate vi
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a', 'â' => 'a', 'ầ' => 'a', 'ấ' => 'a',
            'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a',
            'ẵ' => 'a', 'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ê' => 'e', 'ề' => 'e',
            'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i',
            'ĩ' => 'i', 'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ô' => 'o', 'ồ' => 'o',
            'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o',
            'ở' => 'o', 'ỡ' => 'o', 'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ư' => 'u',
            'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y',
            'ỷ' => 'y', 'ỹ' => 'y', 'đ' => 'd', 'À' => 'A', 'Á' => 'A', 'Ạ' => 'A', 'Ả' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ậ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ă' => 'A', 'Ằ' => 'A',
            'Ắ' => 'A', 'Ặ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'È' => 'E', 'É' => 'E', 'Ẹ' => 'E', 'Ẻ' => 'E',
            'Ẽ' => 'E', 'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ệ' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ì' => 'I',
            'Í' => 'I', 'Ị' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ò' => 'O', 'Ó' => 'O', 'Ọ' => 'O', 'Ỏ' => 'O',
            'Õ' => 'O', 'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ộ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ơ' => 'O',
            'Ờ' => 'O', 'Ớ' => 'O', 'Ợ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Ụ' => 'U',
            'Ủ' => 'U', 'Ũ' => 'U', 'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ự' => 'U', 'Ử' => 'U', 'Ữ' => 'U',
            'Ỳ' => 'Y', 'Ý' => 'Y', 'Ỵ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Đ' => 'D',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );

        $str = str_replace(array_keys($char_map), $char_map, $str);
        return $str;
    }

    public function mb_list_encodings_m()
    {
        $list = array('pass',
            'auto',
            'wchar',
            'byte2be',
            'byte2le',
            'byte4be',
            'byte4le',
            'BASE64',
            'UUENCODE',
            'HTML-ENTITIES',
            'Quoted-Printable',
            '7bit',
            '8bit',
            'UCS-4',
            'UCS-4BE',
            'UCS-4LE',
            'UCS-2',
            'UCS-2BE',
            'UCS-2LE',
            'UTF-32',
            'UTF-32BE',
            'UTF-32LE',
            'UTF-16',
            'UTF-16BE',
            'UTF-16LE',
            'UTF-8',
            'UTF-7',
            'UTF7-IMAP',
            'ASCII',
            'EUC-JP',
            'SJIS',
            'eucJP-win',
            'SJIS-win',
            'CP932',
            'CP51932',
            'JIS',
            'ISO-2022-JP',
            'ISO-2022-JP-MS',
            'Windows-1252',
            'Windows-1254',
            'ISO-8859-1',
            'ISO-8859-2',
            'ISO-8859-3',
            'ISO-8859-4',
            'ISO-8859-5',
            'ISO-8859-6',
            'ISO-8859-7',
            'ISO-8859-8',
            'ISO-8859-9',
            'ISO-8859-10',
            'ISO-8859-13',
            'ISO-8859-14',
            'ISO-8859-15',
            'ISO-8859-16',
            'EUC-CN',
            'CP936',
            'HZ',
            'EUC-TW',
            'BIG-5',
            'EUC-KR',
            'UHC',
            'ISO-2022-KR',
            'Windows-1251',
            'CP866',
            'KOI8-R',
            'KOI8-U',
            'ArmSCII-8',
            'CP850',
            'JIS-ms',
            'CP50220',
            'CP50220raw',
            'CP50221',
            'CP50222');
        return $list;
    }

}

?>