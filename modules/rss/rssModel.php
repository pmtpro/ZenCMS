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

Class rssModel Extends ZenModel
{

	public function get_links() {
		$sql = "SELECT * FROM ".tb()."blogs ORDER BY `time` DESC LIMIT 10";
		$query = $this->db->query($sql);
        if (!$this->db->num_row($query)) {
            return array();
        }
        $out = array();
        while ($row = $this->db->fetch_array($query)) {
            $out[] = $this->gdata($row);
        }
        return $out;
    }

	public function gdata($data = array()) {
        $ro = $this->db->sqlQuoteRm($data);
        if (isset($ro['url'])) {
            $ro['full_url'] = HOME . '/' . $ro['url'] . '-' . $ro['id'] . '.html';
        }
        if (isset($ro['icon'])) {
            if (empty($ro['icon'])) {
                $ro['full_icon'] = _BASE_TEMPLATE . '/images/' . tplConfig('default_icon');
            } else {
                $ro['full_icon'] = HOME . '/files/posts/images/' . $ro['icon'];
            }
        }
        if (isset($ro['content'])) {
            $ro['sub_content'] = subWords(removeTag($ro['content']), 10);
        }
        return $ro;
    }
}