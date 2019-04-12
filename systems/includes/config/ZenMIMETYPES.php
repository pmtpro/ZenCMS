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

$zen['config']['mimes'] = array(
    'hqx' => array(
        'application/mac-binhex40',
        'application/octet-stream'),
    'cpt' => array(
        'application/mac-compactpro',
        'application/octet-stream'),
    'csv' => array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv', 'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'application/octet-stream'),

    'bin' => array(
        'application/macbinary',
        'application/octet-stream'),
    'dms' => array(
        'application/octet-stream',
        'application/octet-stream'),
    'lha' => array('application/octet-stream'),
    'lzh' => array('application/octet-stream'),
    'exe' => array(
        'application/octet-stream',
        'application/x-msdownload'),

    'class' => array(
        'application/octet-stream',
        'application/octet-stream'),
    'psd' => array(
        'application/x-photoshop',
        'application/octet-stream'),
    'so' => array(
        'application/octet-stream',
        'application/octet-stream'),
    'sea' => array(
        'application/octet-stream',
        'application/octet-stream'),
    'dll' => array('application/octet-stream'),
    'oda' => array('application/oda'),

    'ai' => 'application/postscript',
    'eps' => 'application/postscript',
    'ps' => 'application/postscript',
    'smi' => 'application/smil',
    'smil' => 'application/smil',
    'mif' => 'application/vnd.mif',

    'ppt' => array(
        'application/powerpoint',
        'application/vnd.ms-powerpoint',
        'application/octet-stream'),
    'wbxml' => 'application/wbxml',
    'wmlc' => 'application/wmlc',
    'dcr' => 'application/x-director',
    'dir' => 'application/x-director',
    'dxr' => 'application/x-director',
    'dvi' => 'application/x-dvi',
    'sit' => 'application/x-stuffit',

    'cab' => array(
        'application/vnd.ms-cab-compressed',
        'application/octet-stream'),
    'mid' => array(
        'audio/midi',
        'application/octet-stream'),
    'midi' => array(
        'audio/midi',
        'application/octet-stream'),
    'mpga' => array(
        'audio/mpeg',
        'application/octet-stream'),
    'mp2' => 'audio/mpeg',
    'mp3' => array('audio/mpeg',
        'audio/mpg', 'audio/mpeg3',
        'audio/mp3'),
    'aif' => array(
        'audio/x-aiff',
        'application/octet-stream'),
    'aiff' => array(
        'audio/x-aiff',
        'application/octet-stream'),
    'aifc' => array(
        'audio/x-aiff',
        'application/octet-stream'),
    'ram' => array(
        'audio/x-pn-realaudio',
        'application/octet-stream'),
    'rm' => array(
        'audio/x-pn-realaudio',
        'application/octet-stream'),
    'rpm' => array(
        'audio/x-pn-realaudio-plugin',
        'application/octet-stream'),
    'ra' => array(
        'audio/x-realaudio',
        'application/octet-stream'),
    'rv' => array(
        'video/vnd.rn-realvideo',
        'application/octet-stream'),
    'wav' => array(
        'audio/x-wav',
        'audio/wave',
        'audio/wav',
        'application/octet-stream'),

    'flv' => array(
        'video/x-flv',
        'application/octet-stream'),
    '3gp' => array(
        'audio/3gpp',
        'video/3gpp',
        'application/octet-stream'),
    'swf' => array(
        'application/x-shockwave-flash',
        'application/octet-stream'),
    'mpeg' => array(
        'video/mpeg',
        'application/octet-stream'),
    'mpg' => array(
        'video/mpeg',
        'application/octet-stream'),
    'mpe' => array(
        'video/mpeg',
        'application/octet-stream'),
    'qt' => array(
        'video/quicktime',
        'application/octet-stream'),
    'mov' => array(
        'video/quicktime',
        'application/octet-stream'),
    'avi' => array(
        'video/x-msvideo',
        'application/octet-stream'),
    'movie' => array(
        'video/x-sgi-movie',
        'application/octet-stream'),

    'bmp' => array('image/bmp',
        'image/x-bmp',
        'image/x-bitmap',
        'image/x-xbitmap',
        'image/x-win-bitmap',
        'image/x-windows-bmp',
        'image/ms-bmp',
        'image/x-ms-bmp',
        'application/bmp',
        'application/x-bmp',
        'application/x-win-bitmap',
        'application/octet-stream'),
    'gif' => array(
        'image/gif',
        'application/octet-stream'),
    'jpeg' => array(
        'image/jpeg',
        'image/pjpeg',
        'application/octet-stream'),
    'jpg' => array(
        'image/jpeg',
        'image/pjpeg',
        'application/octet-stream'),
    'jpe' => array(
        'image/jpeg',
        'image/pjpeg',
        'application/octet-stream'),
    'png' => array(
        'image/png',
        'image/x-png',
        'application/octet-stream'),
    'ico' => array(
        'image/ico',
        'image/x-icon',
        'application/ico',
        'application/x-ico',
        'application/octet-stream'),
    'tiff' => array(
        'image/tiff',
        'application/octet-stream'),
    'tif' => array(
        'image/tiff',
        'application/octet-stream'),

    'log' => array(
        'text/plain',
        'text/x-log',
        'application/octet-stream'),
    'rtx' => array(
        'text/richtext',
        'application/octet-stream'),
    'rtf' => array(
        'text/rtf',
        'application/octet-stream'),
    'xsl' => array(
        'application/xml',
        'text/xml',
        'text/xsl',
        'application/octet-stream'),
    'doc' => array(
        'application/msword',
        'application/mswor2C sc2',
        'application/wordperfect5.1',
        'application/x-detective',
        'application/x-pressleaf',
        'application/x-soffice',
        'text/plain',
        'application/octet-stream'),
    'docx' => array(
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip'),
    'xlsx' => array(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/octet-stream'),
    'word' => array(
        'application/msword',
        'application/octet-stream'),
    'xl' => array(
        'application/excel',
        'application/octet-stream'),
    'eml' => array(
        'message/rfc822',
        'application/octet-stream'),
    'pdf' => array(
        'application/pdf',
        'application/x-download',
        'application/octet-stream'),
    'xls' => array(
        'application/excel',
        'application/vnd.ms-excel',
        'application/msexcel',
        'application/octet-stream'),

    'php' => array(
        'text/php',
        'text/x-php',
        'application/x-php',
        'application/x-httpd-php',
        'application/x-httpd-php-source',
        'application/octet-stream'),
    'php4' => array(
        'application/x-httpd-php',
        'application/x-httpd-php4-preprocessed',
        'text/php',
        'application/octet-stream'),
    'php3' => array(
        'application/x-httpd-php',
        'application/x-httpd-php3',
        'application/x-httpd-php3',
        'text/php',
        'application/octet-stream'),
    'phtml' => array(
        'application/x-httpd-php',
        'application/x-httpd-eperl',
        'text/html',
        'application/octet-stream'),
    'phps' => array(
        'application/x-httpd-php-source',
        'application/x-httpd-php3-source',
        'text/html',
        'application/octet-stream'),
    'js' => array(
        'application/x-javascript',
        'text/javascript',
        'text/Jscript',
        'application/ecmascript',
        'text/ecmascript',
        'application/octet-stream'),
    'xhtml' => array(
        'application/xhtml+xml',
        'application/vnd.pwg-xhtml-print+xml',
        'text/html',
        'text/x-xhtml',
        'application/octet-stream'),
    'xht' => array(
        'application/xhtml+xml',
        'application/octet-stream'),
    'css' => array(
        'text/css',
        'application/pointplus',
        'application/x-pointplus',
        'application/x-comet',
        'application/x-comet#182v',
        'widetext/css',
        'x-application/css',
        'application/octet-stream'),
    'html' => array(
        'text/html',
        'widetext/html',
        'text/plain',
        'application/octet-stream'),
    'htm' => array(
        'text/html',
        'widetext/html',
        'text/plain',
        'application/octet-stream'),
    'xml' => array(
        'text/xml',
        'magnus-internal/xmlform',
        'text/x-pidl',
        'widetext/xml',
        'application/octet-stream'),
    'shtml' => array(
        'text/html',
        'magnus-internal/parsed-html',
        'text/x-server-parsed-html',
        'application/octet-stream'),
    'txt' => array(
        'text/plain',
        'text/anytext',
        'widetext/paragraph',
        'widetext/plain',
        'application/octet-stream'),
    'text' => array(
        'text/plain',
        'application/octet-stream'),
    'json' => array(
        'application/json',
        'text/json',
        'application/octet-stream'),
    'sql' => array(
        'application/soffice',
        'application/x-soffice',
        'application/x-staroffice',
        'text/plain',
        'application/octet-stream'),

    'jar' => array(
        'application/java-archive',
        'application/zip',
        'application/x-java-archive',
        'application/octet-stream'),
    'jad' => array(
        'text/vnd.sun.j2me.app-descriptor',
        'application/octet-stream'),
    'sis' => array(
        'application/vnd.symbian.install',
        'x-epoc/x-app',
        'x-epoc/x-sisx-app',
        'application/octet-stream'),
    'sisx' => array(
        'x-epoc/x-sisx-app',
        'application/vnd.symbian.install',
        'application/octet-stream'),
    'nth' => array(
        'application/vnd.nok-s40theme',
        'application/octet-stream'),
    'ipa' => array(
        'application/x-ipay',
        'application/zip',
        'application/octet-stream'),
    'apk' => array(
        'application/vnd.android.package-archive',
        'application/zip',
        'application/x-gsarcade-usersvc',
        'application/octet-stream'),
    'tar' => array(
        'application/tar',
        'application/x-tar',
        'applicaton/x-gtar',
        'multipart/x-tar',
        'application/x-compress',
        'application/x-compressed',
        'application/octet-stream'),
    'tgz' => array(
        'application/x-tar',
        'application/x-compressed',
        'application/x-gzip-compressed',
        'application/octet-stream'),
    'gtar' => array(
        'application/x-gtar',
        'application/octet-stream'),
    'gz' => array(
        'application/gzip',
        'application/x-gtar',
        'application/x-gzip',
        'application/x-gunzip',
        'application/gzipped',
        'application/gzip-compressed',
        'application/x-compressed',
        'application/x-compress',
        'gzip/document',
        'application/octet-stream'),
    'zip' => array(
        'application/zip',
        'application/x-zip',
        'application/x-zip-compressed',
        'multipart/x-zip',
        'application/x-compress',
        'application/x-compressed',
        'application/octet-stream'),
    'rar' => array(
        'application/rar',
        'application/x-compressed',
        'application/x-rar',
        'application/x-rar-compressed',
        'application/x-',
        'compressed/rar',
        'application/x-rar-compressed',
        'application/octet-stream')
);
