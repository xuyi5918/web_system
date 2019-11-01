<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/7/18 13:18
 */
class scaffold extends Core_Controller
{
    public $scaffoldMap = array(
        'model' => 'create_model_class'
    );



    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 创建项目
     * @param $type
     * @param $object
     * @param $table
     * @author xuyi
     * @date 2019/7/18
     */
    public function create_object($scaffoldType, $object, $table)
    {

        $scaffoldClass = !isset($this->scaffoldMap[$scaffoldType]) ? '' : $this->scaffoldMap[$scaffoldType];
        $object = trim($object);
        $table = trim($table);

        if(empty($scaffoldClass)) {
            echo "scaffold type : {$scaffoldType} not";
        }


        $paramArr = array();
        $scaffold = self::classes($scaffoldClass, 'scaffold', $paramArr);

        $configuration = array(
            'object' => $object,
            'table' => $table
        );
        $scaffold->configuration($configuration)->execute();
        exit();
    }
}