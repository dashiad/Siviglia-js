<?php
namespace model\web\WebUser;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/WebUser/Definition.php
  CLASS:Definition
*
*
**/

class Definition extends \lib\model\BaseModelDefinition
{
	 static  $definition=array(
               'ROLE'=>'ENTITY',
               'TABLE'=>'WebUser',
               'LABEL'=>'WebUser',
               'SHORTLABEL'=>'WebUser',
               'CARDINALITY'=>'300',
               'INDEXFIELDS'=>array("USER_ID"),
               'CARDINALITY_TYPE'=>'FIXED',
               'FIELDS'=>array(
                     'LOGIN'=>array(
                           'DEFAULT'=>'',
                           'MINLENGTH'=>'3',
                           'LABEL'=>'LOGIN',
                           'SHORTLABEL'=>'LOGIN',
                           'MAXLENGTH'=>'15',
                           'TYPE'=>'String',
                           'DESCRIPTIVE'=>'false',
                           'ISLABEL'=>'false'
                           ),
                     'PASSWORD'=>array(
                           'DEFAULT'=>'',
                           'MINLENGTH'=>'3',
                           'LABEL'=>'PASSWORD',
                           'SHORTLABEL'=>'PASSWORD',
                           'MAXLENGTH'=>'16',
                           'TYPE'=>'Password',
                           'DESCRIPTIVE'=>'false',
                           'ISLABEL'=>'false'
                           ),
                     'USER_ID'=>array(
                           'DEFAULT'=>'',
                           'SHORTLABEL'=>'USER_ID',
                           'TYPE'=>'AutoIncrement',
                           'LABEL'=>'USER_ID',
                           'DESCRIPTIVE'=>'false',
                           'ISLABEL'=>'false'
                           ),
                     'EMAIL'=>array(
                           'DEFAULT'=>'',
                           'MINLENGTH'=>'4',
                           'LABEL'=>'EMAIL',
                           'SHORTLABEL'=>'EMAIL',
                           'MAXLENGTH'=>'50',
                           'TYPE'=>'String',
                           'DESCRIPTIVE'=>'false',
                           'ISLABEL'=>'false'
                           ),
                     'FAILEDLOGINATTEMPTS'=>array(
                       'TYPE'=>'Integer',
                       'LABEL'=>'Failed Login Attempts',
                       'DESCRIPTIVE'=>false,
                       'ISLABEL'=>false
                     ),
                     'active'=>array(
                         'DEFAULT'=>'',
                         'SHORTLABEL'=>'active',
                         'TYPE'=>'Boolean',
                         'LABEL'=>'active',
                         'DESCRIPTIVE'=>'false',
                         'ISLABEL'=>'false'
                     ),
                        'lastLogin'=>array(
                            'DEFAULT'=>'',
                            'SHORTLABEL'=>'lastLogin',
                            'TYPE'=>'DateTime',
                            'LABEL'=>'lastLogin',
                            'DESCRIPTIVE'=>'false',
                            'ISLABEL'=>'false'
                        ),

                   'date_add'=>array(
                       'DEFAULT'=>'',
                       'SHORTLABEL'=>'date_add',
                       'TYPE'=>'DateTime',
                       'LABEL'=>'date_add',
                       'DESCRIPTIVE'=>'true',
                       'ISLABEL'=>'true'
                   ),
                   'firstname'=>array(
                       'DEFAULT'=>'',
                       'MINLENGTH'=>'1',
                       'LABEL'=>'firstname',
                       'SHORTLABEL'=>'firstname',
                       'MAXLENGTH'=>'128',
                       'TYPE'=>'String',
                       'DESCRIPTIVE'=>'true',
                       'ISLABEL'=>'true'

                   ),
                   'date_upd'=>array(
                       'DEFAULT'=>'',
                       'SHORTLABEL'=>'date_upd',
                       'TYPE'=>'DateTime',
                       'LABEL'=>'date_upd',
                       'DESCRIPTIVE'=>'true',
                       'ISLABEL'=>'true'
                   ),
                   'lastname'=>array(
                       'DEFAULT'=>'',
                       'MINLENGTH'=>'1',
                       'LABEL'=>'lastname',
                       'SHORTLABEL'=>'lastname',
                       'MAXLENGTH'=>'128',
                       'TYPE'=>'String',
                       'DESCRIPTIVE'=>'true',
                       'ISLABEL'=>'true',
                       'SEARCHABLE'=>true
                   ),
                   'deleted'=>array(
                       'DEFAULT'=>'0',
                       'SHORTLABEL'=>'deleted',
                       'TYPE'=>'Integer',
                       'LABEL'=>'deleted',
                       'DESCRIPTIVE'=>'true',
                       'ISLABEL'=>'true'
                   ),
                   'last_passwd_gen'=>array(
                       'TYPE'=>'DateTime',
                       'LABEL'=>'last_passwd_gen',
                       'SHORTLABEL'=>'last_passwd_gen'
                   )
               ),
               'ALIASES'=>array(

                     ),
                'ICONS'=>array(
                    '16x16'=>null,
                    '32x32'=>null
                ),
               'PERMISSIONS'=>array(),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'ENGINE'=>'InnoDb',
                           'CHARACTER SET'=>'utf8',
                           'COLLATE'=>'utf8_general_ci',
                           'TABLE_OPTIONS'=>array('ROW_FORMAT'=>'FIXED')
                           )
                     )
               );
}
?>
