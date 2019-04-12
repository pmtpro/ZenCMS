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

Class mobigateModel Extends ZenModel
{
	public $_table = 'mobigate';
	public $stt = 0;
	public function app_exist($appid='') {
		$sql = "SELECT * FROM ".tb().$this->_table." WHERE `appid`='".$appid."'";
		$query = $this->db->query($sql);
		if ($this->db->num_row($query)>0) {
			$arr = $this->db->fetch_array($query);
			$this->stt = $arr['stt'];
			return true; //this app is exist
		} else return false; //or not
	}

	public function insert_app($data = array()) {
		if($this->stt == 0) {
			$data_ins = $this->db->sqlQuote($data);
			$sql = $this->db->_sql_insert(tb() . $this->_table, $data_ins);
			$insert_ok = $this->db->query($sql);
			if(!$insert_ok)
			{
				return false;
			}
			else return true;
		}
		else return false;
	}
}