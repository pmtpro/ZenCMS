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
/**
 * $BBCODE = new BbcodeFixer();
 * $string = $BBCODE->parse( $string );
 */
class BbcodeFixer
{
    function BbcodeFixer()
    {
    }

    function parse($str)
    { // considering that $str was already passed through htmlspecialchars();
        $this->_bbcodetag = array('opened' => array());
        $length = strlen($str);
        $position = 0;
        $this->_intag = false;
        $this->_new = null;

        while ($position < $length) {
            $c = $str{$position};
            if ($this->_intag)
                $this->_process_intag($c);
            elseif ($c == '[')
                $this->_intag = $c; else
                $this->_new .= $c;
            ++$position;
        }

        // handle incomplete intags at the end of the string.
        if ($this->_intag)
            $this->_new .= $this->_intag;
        // auto-close any missing (closing) bbcode tags! e.g. "This is in [b]Bold."
        if ($this->_bbcodetag['opened']) {
            $close = array_reverse($this->_bbcodetag['opened']);
            foreach ($close as $code)
                $this->_markup_close_tag($code);
        }
        unset($str);
        return ($this->_new);
    }

    // PRIVATE METHODS
    /////////////////////////////////////////////////////////////
    function _markup_close_tag($code)
    {
        $markup = '';
        if (in_array($code, $this->_bbcodetag['opened'])) { // only process if a matching OPENED tag was found. e.g. the last [/b] in "[b]Bold[/b][/b]" will not be processed.
            switch ($code) {
                case    'b':
                    $markup .= '</strong>';
                    break;
                case    'code':
                    $markup .= '</pre></code>';
                    break;
                case    'font':
                    $markup .= '</span>';
                    break;
                case    'i':
                    $markup .= '</em>';
                    break;
            }
            array_pop($this->_bbcodetag['opened']);
        } else
            $markup = "[/$code]";
        $this->_new .= $markup;
    }

    function _markup_open_tag($code)
    {
        $markup = '';
        $option = null;
        if (false !== strpos($code, '=')) { // extract "font" and "arial" from bbcode tags with options; like "font=arial" for example.
            $tmp = explode('=', $code, 2);
            $code = & $tmp[0];
            $option = & $tmp[1];
        }
        $valid_tag = true;
        switch ($code) {
            case    'b':
                $markup .= '<strong>';
                break;
            case    'code':
                $markup .= '<code><pre>';
                break;
            case    'font':
                $markup .= '<span style="font-family:' .
                    $this->_verifyFont($option) .
                    ';">';
                break;
            case    'i':
                $markup .= '<em>';
                break;
            default:
                if ($option)
                    $option = '=' . $option;
                $markup = "[$code$option]";
                $valid_tag = false;
        }
        if ($valid_tag)
            $this->_bbcodetag['opened'][] = $code;
        $this->_new .= $markup;
    }

    function _process_bbcode_tag()
    {
        $tag = substr($this->_intag, 1);
        if ($tag{0} === '/')
            $this->_markup_close_tag(substr($tag, 1));
        else
            $this->_markup_open_tag($tag);
        $this->_intag = false;
    }

    function _process_intag($c)
    {
        if ($c === ']')
            $this->_process_bbcode_tag();
        elseif ($c === '[') // i.e. the previous "[" character was bogus! e.g. [[b]Something]
        {
            $this->_new .= $this->_intag;
            $this->_intag = $c;
        } else
            $this->_intag .= $c;
    }

    function _verifyFont($option)
    {
        switch ($option) {
            // allow only what we allow as options!
            case    'arial':
            case    'courier':
            case    'courier new':
            case    'times new roman':
            case    'verdana':
                if (false !== strpos($option, ' '))
                    $option = "'$option'"; // e.g. {courier new} is reset to {'courier new'} i.e. with apostrophes
                $font = $option;
                break;
            default:
                $font = 'verdana';
        }
        return ($font);
    }
}