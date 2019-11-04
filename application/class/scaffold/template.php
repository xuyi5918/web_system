<?php

/**
 * Class {@MODEL_NAME@}_model shell auto create
 * @author xuyi
 * @date {@TIME@}
 */
class {@MODEL_NAME@}_model extends Driver_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->getTable();
    }

    {@FIND_FUNCTION_CONTENT@}

    {@SAVE_FUNCTION_CONTENT@}

}
