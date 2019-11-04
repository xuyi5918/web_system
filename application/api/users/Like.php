<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/25 13:25
 */
class Like extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorization();
    }

    public function save_comic_like()
    {

    }
}

