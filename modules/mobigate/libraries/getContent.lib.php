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
class getContent
{
	protected $url = '';
	protected $res = '';
	protected $ua = '';
	protected $ref = '';
	protected $header = array('Content-type: text/html','charset: UTF-8');
	protected $pf = array();
	protected $post = false;
	protected $closed = false;
	public $content = '';
	
	public function __construct()
	{
		$this->res = curl_init();
		$this->ua = $_SERVER['HTTP_USER_AGENT'];
	}
	public function setURL($url) { $this->url = $url; }
	public function setHeader($head) { $this->header = $head; }
	public function setDevice($dv)
	{
		if($dv =='desktop') $this->ua = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.165 CoRom/33.0.1750.165 Safari/537.36';
		if($dv == 'mobile') $this->ua = 'Mozilla/5.0 (Series40; Nokia311/03.81; Profile/MIDP-2.1 Configuration/CLDC-1.1) Gecko/20100401 S40OviBrowser/2.2.0.0.31';
		if($dv == 'touch') $this->ua = 'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
	}
	public function setReferer($ref) { $this->ref = $ref; }
	public function setPostFields($pf) { $this->pf = $pf; }
	public function usePost()	{ $this->post = true; }
	public function Request()
	{
		curl_setopt($this->res,CURLOPT_URL,$this->url);
		curl_setopt($this->res,CURLOPT_HTTPHEADER,$this->header);
		curl_setopt($this->res,CURLOPT_USERAGENT,$this->ua);
		curl_setopt($this->res,CURLOPT_REFERER,$this->ref);
		curl_setopt($this->res,CURLOPT_POST,$this->post);
		curl_setopt($this->res,CURLOPT_POSTFIELDS,$this->pf);
		curl_setopt($this->res,CURLOPT_RETURNTRANSFER,1);
		$this->content = curl_exec($this->res);
		return $this->content;
	}
	public function closeSession()
	{
		curl_close($this->res);
		$this->closed = true;
	}	
	public function __destruct()
	{
		if(!$this->closed) $this->closeSession();
	}
}

?>
