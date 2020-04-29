<?php
namespace model\reflection\DataSource\datasources;
use lib\metadata\MetaDataProvider;

/**
 * FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/AdminFullList.php
* CLASS:AdminFullList
 *
 *
 **/

class DatasourceList
{
    static  $definition=array(
        'ROLE'=>'list',
        'DATAFORMAT'=>'Table',
        'IS_ADMIN'=>1,
        'PARAMS'=>array(
            'model'=>[
                "TYPE"=>"String",
                "PARAMTYPE"=>"DYNAMIC"
            ]
        ),
        'FIELDS'=>array(
            'package'=>array(
                "LABEL"=>"Package",
                'TYPE'=>'String',
            ),
            'model'=>array(
                "LABEL"=>"Model",
                'TYPE'=>'String'
            ),
            'name'=>array(
                "LABEL"=>"Name",
                'TYPE'=>"String",
            ),
            'className'=>array(
                "LABEL"=>"Class",
                'TYPE'=>"String",
            )
        ),
        'PERMISSIONS'=>array(
            array(
                'MODEL'=>'Site',
                'PERMISSION'=>'REFLECTION'
            )
        ),
        'SOURCE'=>[

            'METHOD'=>array(
                'DEFINITION'=>array(
                    "MODEL"=>'self',
                    "METHOD"=>'getDatasourceList'
                )
            )

            ]
    );

    function getDatasourceList($ds)
    {
        $list=[];
        $filter=null;
        if($ds->model!==null)
            $filter=$ds->model;
        $mdProv=new \lib\metadata\MetaDataProvider();
        $info=$mdProv->getDatasource(
            \lib\metadata\MetaDataProvider::GET_LIST,
            $ds->model,
            null,null,null);
        for($k=0;$k<count($info);$k++)
        {
            $c=$info[$k];
            $list[]=[
                'package'=>$c["package"],
                'model'=>$ds->model,
                'name'=>$c["item"],
                'className'=>str_replace('\\','/',$c["class"])
                ];
        }
        return $list;
    }
}
?>
