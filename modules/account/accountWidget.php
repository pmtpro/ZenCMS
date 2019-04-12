<?php 

if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

Class accountWidget Extends ZenModel
{
	public function user_info()
	{
        $user = $this->user;
		return $user;
	}
}
