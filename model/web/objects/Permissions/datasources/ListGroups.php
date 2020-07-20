<?php
namespace model\web\Permissions\datasources;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site/datasources/FullList.php
  CLASS:FullList
*
*
**/

class ListGroups
{
	 static  $definition=array(
               'ROLE'=>'list',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                     'group_type'=>array(
                           'TYPE'=>"Integer",
                           'TRIGGER_VAR'=>'group_type'
                           ),
                     'group_charPath'=>array(
                           'TYPE'=>'String',
                           'TRIGGER_VAR'=>'group_charPath'
                           )
                     ),
               'IS_ADMIN'=>0,
               'FIELDS'=>array(
                     'id'=>array(
                           'TYPE'=>'Integer'
                           ),

                     'group_name'=>array(
                           'TYPE'=>'String'
                           ),
                   'group_type'=>array(
                       'TYPE'=>'Integer'
                   ),
                   'group_parent'=>array(
                       'TYPE'=>'Integer'
                   ),

                     'group_path'=>array(
                           'TYPE'=>'String'
                           ),
                     'group_charPath'=>array(
                           'TYPE'=>'String'
                           )
                     ),
               'PERMISSIONS'=>array(["TYPE"=>"Public"]),
               'SOURCE'=>[
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'_permission_groups',
                                 'BASE'=>array(
                                       '*'
                                       ),
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'group_type',
                                                   'OP'=>'=',
                                                   'V'=>'[%group_type%]'
                                                   ),
                                             'TRIGGER_VAR'=>'group_type',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'group_type'
                                             ),
                                       array(
                                             'FILTER'=>array(
                                                   'F'=>'group_charPath',
                                                   'OP'=>'=',
                                                   'V'=>'[%group_charPath%]'
                                                   ),
                                             'TRIGGER_VAR'=>'group_charPath',
                                             'DISABLE_IF'=>'0',
                                             'FILTERREF'=>'group_charPath'
                                             )
                                       )
                                 )
                           )
                     )
                   ]
               );
}