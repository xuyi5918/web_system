<?php

class TRACE_info
{
    private $attrArr = array();
    private $total = 1;

    private $debug = FALSE;


    /**
     *
     * @param $field
     * @param $content
     * @return bool
     */
    public function logs($field, $content)
    {
        $this->debug = isset($_GET['debug']) ? TRUE : FALSE;
        if($this->debug)
        {
            if(! empty($content))
            {
                $this->attrArr[$field][] = $content;
            }
        }

        return FALSE;
    }

    /**
     *
     * @return bool
     */
    public function displayTrace()
    {
        if($this->debug)
        {
            echo  json_encode($this->attrArr);
        }


        return FALSE;
    }
}
