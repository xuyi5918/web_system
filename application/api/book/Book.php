<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/9/15 14:12
 */
class Book extends Driver_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * book show
     * @author xuyi
     * @date 2019/9/24
     */
    public function book_show()
    {
        $bookId = self::get('book_id', 0, 'intval');

        $this->displayJson(function() use($bookId)
        {

        }, CACHE_OUT_TIME_THIRTY_MINU);
    }
}
