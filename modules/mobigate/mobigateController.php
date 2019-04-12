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

Class mobigateController Extends ZenController
{
	protected $_page_size = 10;
	protected $apikey = '';
    protected $base_url = '/admin/general/modulescp?appFollow=mobigate';
    function index() {
        $base_url = HOME . $this->base_url;
        $data['base_url'] = $base_url;
		$this->apikey = $_COOKIE['apikey'];
		if(empty($this->apikey)) {
			$this->change_api(); //set api
		} else {
			$security = load_library('security');
			$model = $this->model->get('blog');
			$mobigateModel = $this->model->get('mobigate');
			if(isset($_GET['appid'])) {

				$appID = $security->cleanXSS($_GET['appid']);
                $data['app'] = $this->get_detail($appID);
				if($mobigateModel->app_exist($appID)) {
					ZenView::set_error('Bạn đã phân phối game này, Vui lòng kiểm tra lại','result', HOME . '/admin/general/modulescp?appFollow=mobigate/index');
				} else {
                    ZenView::set_tip('Phân phối game <b>' . $data['app']->name . '</b> của <b>' . $data['app']->provider . '</b>. Game/ứng dụng này hỗ trợ HĐH <b>' . implode(', ', $data['app']->supportedOs) . '</b>', 'select-cat');
					$data['appid'] = $appID;
					$data['tree_folder'] = $model->get_tree_folder();
					if(isset($_POST['catid'])) {
						$catid = (int)$_POST['catid'];
						$ok = $this->save_app($appID,$catid);
						if($ok) {
							ZenView::set_success('Viết bài thành công!','result', HOME . '/admin/general/modulescp?appFollow=mobigate/index');
						} else ZenView::set_error('Không thể viết bài!','result');
					}
				}
			} else {
				//get api
				$url = 'http://mobigate.vn/api/export/zencms/list?apiKey='.$this->apikey.'&page=1&pageSize=1000&orderBy=view&orderType=DESC';
				$api = $this->get_api($url);
				$data['items'] = $api->items;
                if (empty($data['items'])) {
                    ZenView::set_notice('Không thể load API. Vui lòng kiểm tra lại!', 'list-app');
                }
			}
            ZenView::set_title('Phân phối game từ mobigate');
            ZenView::set_breadcrumb(url($base_url . '/index', 'Mobigate'));
			$this->view->data = $data;
			$this->view->show('mobigate/index');
		}
    }

	function change_api() {
		ZenView::set_title('Cài đặt API Key Mobigate');
		$this->apikey = $_COOKIE['apikey'];
		if(isset($_POST['apikey'])) {
			$set_ok = $this->set_api($_POST['apikey']);
			if($set_ok) {
					ZenView::set_success('API Key của bạn là '.$this->apikey.'','set_api', HOME . $this->base_url);
			}
			else ZenView::set_error('API Key không hợp lệ!','set_api');
		}
		$data['cur_api'] = $this->apikey;
		$this->view->data = $data;
        $this->view->show('mobigate/change_api');
	}

	function set_api($apikey) {
		$g = load_library('getContent', array('module' => 'mobigate'));
		$url = 'http://mobigate.vn/api/export/zencms/list?apiKey='.$apikey.'&type=online&page=1&pageSize=5&orderBy=view&orderType=DESC';
		$g->setURL($url);
		$g->Request();
		json_decode($g->content);
		if(json_last_error()==JSON_ERROR_NONE) {
			$this->apikey = $apikey;
			setcookie('apikey',$apikey,time()+86400*7,'/');
			return true;
		} else return false;
	}

	function get_api($url) {
		$g = load_library('getContent', array('module' => 'mobigate'));
        /**
         * get cache
         */
        $cache = ZenCaching::get($url);
        if ($cache != null) {
            return $cache;
        }
		$g->setURL($url);
		$g->Request();
		$data = json_decode($g->content);
        /**
         * set the new cache
         */
        ZenCaching::set($url, $data, 12*60*60);
		return $data;
	}

    public function get_detail($appID) {
        $url = 'http://mobigate.vn/api/export/zencms/detail?apiKey='.$this->apikey.'&itemId='.$appID;
        $api = $this->get_api($url);
        return $api;
    }

	function save_app($appID,$parent) {
		$security = load_library('security');
		$seo = load_library('seo');
		$model_blog = $this->model->get('blog');
		$model_mgate = $this->model->get('mobigate');
        $blogHook = $this->hook->get('blog');
        $user = $this->user;
        /**
         * get detail app
         */
        $api = $this->get_detail($appID);
		$data['name'] = h($blogHook->loader('valid_name', $api->name));
		$data['url'] = $seo->url($data['name']);
        $data['uid'] = $user['id'];
		$data['parent'] = (int)$security->removeSQLI($parent);
		$data['time'] = time();

		//create content
		$supported = $api->supportedOs;
		$content = '<b>Giới thiệu:</b> <br />'.$api->description.'<br />';
		$content .= '<b>Nhà cung cấp:</b> '.$api->provider.'<br />';
		$content .= '<b>Hỗ trợ HĐH:</b> ';
					foreach($supported as $os)
						$content .= $os.' ';
		$content .='<br />';
		$content .='<b>Download: </b> <a href="'.$api->downloadUrl.'" rel="nofollow" title="Tải game '.$api->name.'">Click here</a>';
		$data['content'] = $content;
		//upload icon
		$imageUploadDir = __FILES_PATH . '/posts/images';
		$filename = $data['url'];
		$upload = load_library('upload', array('init_data' => $api->avatar));
		if ($upload->uploaded) {
			$upload->file_new_name_body = $filename;
            $upload->allowed = array('image/*');
			$subDir = autoMkSubDir($imageUploadDir);
            $upload->process($imageUploadDir . '/' . $subDir);
			if($upload->processed) {
				$dataUp = $upload->data();
				$data['icon'] = $subDir . '/' . $dataUp['file_name'];
			}
		}
		$inserted_blog = $model_blog->insert_blog($data);
		$app['appid'] = $api->requestId;
		$app['stt'] = 2;
		$inserted_data = $model_mgate->insert_app($app);
		if($inserted_blog && $inserted_data) {
            $insertLink = array(
                'name' => 'Tải về ' . $api->name,
                'link' => $api->downloadUrl,
                'sid' => $inserted_blog,
                'uid' => $user['id'],
                'time' => time()
            );
            $model_blog->insert_link($insertLink);
			return true; 
		} else return false;
	}
}

