<?php
/**
 * Created by PhpStorm.
 * author: xuyi
 * date: 2019/7/18 13:20
 */
class create_model_class
{
    public $load;
    public $paramArr = array();
    public $content = NULL;
    public $author = '';
    public $createAt = '';
    public $typeMap = array(
        'char'     =>'string',
        'varchar' => 'string',
        'int'     => 'int',
        'tinyint' => 'int',
        'text'    =>'string'
    );

    public $defaultValMap = array(
        'string' => "''",
        'int'    => 0,
    );

    public $buildColumnsMap = array();

    public function __construct()
    {
        $this->load = app();
        $this->author = 'shell auto create';
        $this->createAt = date('Y-m-d H:i:s');

    }

    public function configuration($configuration)
    {
        $this->paramArr = $configuration;
        return $this;
    }

    public function execute()
    {

        $parsing = $this->parsing();

        if(! validate($parsing)) {
            return $parsing;
        }


        $build = $this->build();
        if(! validate($build)) {
           return $build;
        }

        $write = $this->write();
        if(! validate($write)) {
            return $write;
        }

        return successMsg('ok');
    }

    public function getDefaultVal($type)
    {
        return $this->defaultValMap[$type];
    }


    public function parsing()
    {

        $object = empty($this->paramArr['object']) ? '' : $this->paramArr['object'];
        $table  = empty($this->paramArr['table']) ? '' : $this->paramArr['table'];

        $this->content = file_get_contents(dirname(__FILE__).'/template.php');

        $this->load->model('scaffold_model', 'scaffold');

        $objectList = $this->load->scaffold_model->getObjectInfoListByObject($object, $table);

        foreach ($objectList as $item=>$value)
        {
            $columnsName = empty($value['COLUMN_NAME']) ? '' : $value['COLUMN_NAME'];
            $dateType = empty($value['DATA_TYPE']) ? '' : $value['DATA_TYPE'];

            $columnsMap[$columnsName] = array(
                'TYPE'          => empty($this->typeMap[$dateType]) ? $dateType : $this->typeMap[$dateType],
                'COLUMN_KEY'    => empty($value['COLUMN_KEY']) ? FALSE : $value['COLUMN_KEY'],
                'COLUMN_COMMENT' => empty($value['COLUMN_COMMENT']) ? '' : $value['COLUMN_COMMENT']
            );
        }


        $this->buildColumnsMap = $columnsMap;

        return successMsg('ok');

    }

    public function getEmptyBlockContent($column,$varName)
    {
        $if = file_get_contents(dirname(__FILE__).'/tags_template.php');
        $matchRes = preg_match_all('/{@empty-function-block}([\s\S]*){empty-function-block@}/', $if,$result);
        $template = '';

        if(! empty($matchRes))
        {
            $str = empty($result[1][0]) ? '' : $result[1][0];
            foreach ($column as $item=>$value)
            {
                $toHump = lcfirst(toHump($item));
                $replaceResult = str_replace('{@VAR_ITEM@}', $toHump, $str);
                $replaceResult = str_replace('{@VAR@}', $varName, $replaceResult);
                $replaceResult = str_replace('{@ITEM@}', $item, $replaceResult);

                $template .= str_replace('{@DEFAULT@}', $this->getDefaultVal($value['TYPE']), $replaceResult);
            }
        }
        return $template;

    }
    public function getIfBlockContent($column)
    {
       $if = file_get_contents(dirname(__FILE__).'/tags_template.php');
       $matchRes = preg_match_all('/{@update-if-block}([\s\S]*){update-if-block@}/', $if,$result);
       $template = '';
       if(! empty($matchRes))
       {
           $str = empty($result[1][0]) ? '' : $result[1][0];
            foreach ($column as $item=>$value)
            {
                if($value['COLUMN_KEY']=='PRI') {
                    continue;
                }

                $toHump = lcfirst(toHump($item));
                $replaceResult = str_replace('{@UPDATE_ITEM@}', $toHump, $str);
                $template .= str_replace('{@UPDATE_KEY@}', $item, $replaceResult);
            }
       }
       return $template;
    }

    public function getArrayBlockContent($column)
    {
        $if = file_get_contents(dirname(__FILE__).'/tags_template.php');
        $matchRes = preg_match_all('/{@update-insert-array-block}([\s\S]*){update-insert-array-block@}/', $if,$result);
        $template = '';
        if(! empty($matchRes))
        {
            $str = empty($result[1][0]) ? '' : $result[1][0];
            foreach ($column as $item=>$value)
            {
                if($value['COLUMN_KEY']=='PRI') {
                    continue;
                }

                $toHump = lcfirst(toHump($item));
                $replaceResult = str_replace('{@ARRAY-VAR@}', $toHump, $str);
                $template .= str_replace('{@ARRAY-KEY@}', $item, $replaceResult);
            }
        }
        return $template;
    }


    public function build()
    {
        $buildColumnsMap = $this->buildColumnsMap;
        $table  = empty($this->paramArr['table']) ? '' : $this->paramArr['table'];

        # 根据主键查询方法生成
        $this->createSelectPkRowAndList($buildColumnsMap, $table);

        $this->createEditStatus($buildColumnsMap, $table);

        return successMsg('ok');

    }

    public function getColumnComment($comment)
    {
        $commentList = explode(',', $comment);

        $resultList = array();
        foreach ($commentList as $commentRow) {
             $result = explode(':', $commentRow);
             $temp = trim($result[0]);
             $resultList[] = is_numeric($temp) ? intval($temp) : "'{$temp}'";
        }
        return $resultList;
    }

    /**
     * 根据主键修改数据
     * @param $columnsMapList
     * @param $table
     * @author xuyi
     * @date 2019/9/5
     */
    public function createEditStatus($columnsMapList, $table)
    {
        $replaceMapList = array();
        $replaceMapList['{@TABLE_NAME@}'] = toHump($table);
        $replaceMapList['{@MODEL_NAME@}'] = ucfirst($table);
        $replaceMapList['{@AUTHOR@}'] = $this->author;
        $replaceMapList['{@DATE_NAME@}'] = $this->paramArr['object'];
        $replaceMapList['{@TIME@}'] = $this->createAt;

        $func = file_get_contents(dirname(__FILE__).'/save_class_function_template.php');

        foreach ($columnsMapList as $item=>$columnsRow)
        {

            $result = strrpos($item, 'status');
            if($result !== FALSE)
            {
               $columnCommentList = $this->getColumnComment($columnsRow['COLUMN_COMMENT']);
               $replaceMapList['{@STATUS_LIST@}'] = implode(', ', $columnCommentList);
               $replaceMapList['{@EDIT_COLUMN@}'] = $item;
               $replaceMapList['{@EDIT_BY_COLUMN@}'] = ucfirst(toHump($item));
            }

            if(!empty($columnsRow['COLUMN_KEY']) && $columnsRow['COLUMN_KEY'] == 'PRI')
            {
                $replaceMapList['{@PK_COLUMN@}'] = lcfirst(toHump($item));
                $replaceMapList['{@FIND_COLUMN@}'] = lcfirst(toHump($item));
                $replaceMapList['{@FIELDS_COLUMN@}'] = $item;
                $replaceMapList['{@BY_COLUMN@}'] = toHump($item);
                $replaceMapList['{@PK_COLUMN_FIND@}'] = $item;
            }


        }

        $buildCode = $func;
        foreach ($replaceMapList as $item=>$row)
        {
            $buildCode = str_replace($item, $row, $buildCode);
        }


        $content = $this->getIfBlockContent($columnsMapList);
        $buildCode = str_replace('{if-block}', trim($content), $buildCode);

        $content = $this->getEmptyBlockContent($columnsMapList, 'saveData');
        $buildCode = str_replace('{is-empty-param}', trim($content), $buildCode);

        $content = $this->getArrayBlockContent($columnsMapList);
        $buildCode = str_replace('{array-block}', trim($content), $buildCode);


        $this->content = str_replace('{@SAVE_FUNCTION_CONTENT@}', $buildCode, $this->content);
    }

    public function createSelectPkRowAndList($columnsMapList, $table)
    {

        $replaceMapList = array();
        $replaceMapList['{@TABLE_NAME@}'] = toHump($table);
        $replaceMapList['{@MODEL_NAME@}'] = ucfirst($table);
        $replaceMapList['{@AUTHOR@}'] = $this->author;
        foreach ($columnsMapList as $item=>$columnsRow)
        {
            if(!empty($columnsRow['COLUMN_KEY']))
            {

                $replaceMapList['param'][] = array(
                    '{@BY_COLUMN@}' => toHump($item),
                    '{@PK_COLUMN@}' => lcfirst(toHump($item)),
                    '{@PK_COLUMN_FIND@}' => $item
                );

            }
        }



        $replaceMapList['{@DATE_NAME@}'] = $this->paramArr['object'];
        $replaceMapList['{@TIME@}'] = $this->createAt;

        foreach ($replaceMapList as $item=>$value)
        {
            if($item == 'param')
            {
                $function = '';
                foreach ($value as $key=>$row)
                {
                    $func = file_get_contents(dirname(__FILE__).'/find_class_function_template.php');
                    foreach ($row as $k=>$v)
                    {
                        $func = str_replace($k, $v, $func);
                    }
                    $function .= $func;
                }

                $this->content = str_replace("{@FIND_FUNCTION_CONTENT@}", $function, $this->content);
                continue;
            }
        }

        foreach ($replaceMapList as $item=>$value)
        {

            if($item === 'param') {
                continue;
            }
            $this->content = str_replace($item, $value, $this->content);
        }

        return successMsg('ok');
    }

    public function write()
    {

        $object = empty($this->paramArr['object']) ? '' : $this->paramArr['object'];
        $table  = empty($this->paramArr['table']) ? '' : $this->paramArr['table'];


        $write = APPPATH . 'models/' .  $object.'/' . ucfirst($table) . '_model.php';
        echo $write;

        file_put_contents($write, $this->content);

        return successMsg('ok');
    }
}
