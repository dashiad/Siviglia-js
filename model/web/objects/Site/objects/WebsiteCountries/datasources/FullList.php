<?php
namespace model\web\Site\WebsiteCountries\datasources;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Sites/objects/WebsiteCountries/datasources/FullList.php
  CLASS:FullList
*
*
**/

class FullList
{
	 static  $definition=array(
               'ROLE'=>'list',
               'DATAFORMAT'=>'Table',
               'PARAMS'=>array(
                   'id_website'=>array(
                       "MODEL"=>'Sites',
                       "FIELD"=>"id_website",
                       'TRIGGER_VAR'=>'id_website'
                        ),
                   'active'=>array(
                       'MODEL'=>'\model\web\ps_customer\ps_state',
                       'FIELD'=>'active',
                       'TRIGGER_VAR'=>'active'
                        )
                    ),
               'IS_ADMIN'=>0,
               'FIELDS'=>array(
                     'id_country'=>array(
                           'MODEL'=>'\model\web\ps_customer\ps_country',
                           'FIELD'=>'id_country'
                           ),
                     'name'=>array(
                           'MODEL'=>'\model\web\ps_customer\ps_state',
                           'FIELD'=>'name'
                           ),
                     'id_state'=>array(
                           'MODEL'=>'\model\web\ps_customer\ps_state',
                           'FIELD'=>'id_state'
                           )
                     ),
               'INCLUDE'=>array(
                     'ps_state_id_country'=>array(
                         'MODEL'=>'\model\web\ps_customer\ps_state',
                         'DATASOURCE'=>'FullList',
                         'JOINTYPE'=>'LEFT',
                         'JOIN'=>array('id_country'=>'id_country')
                        ),
                     'Websites_id_website'=>array(
                         'MODEL'=>'\Sites',
                         'DATASOURCE'=>'FullList',
                         'JOINTYPE'=>'LEFT',
                         'JOIN'=>array('id_website'=>'id_website')
                        ),
                     'ps_country_id_country'=>array(
                         'MODEL'=>'\model\web\ps_customer\ps_country',
                         'DATASOURCE'=>'FullList',
                         'JOINTYPE'=>'LEFT',
                         'JOIN'=>array('id_country'=>'id_country')
                        ),
                     'ps_country_lang_id_country'=>array(
                         'MODEL'=>'\model\web\ps_customer\ps_country_lang',
                         'DATASOURCE'=>'FullList',
                         'JOINTYPE'=>'LEFT',
                         'JOIN'=>array('id_country'=>'id_country', 'id_lang'=>'id_lang')
                        )
                     ),
               'PERMISSIONS'=>array('_PUBLIC_'),
               'STORAGE'=>array(
                     'MYSQL'=>array(
                           'DEFINITION'=>array(
                                 'TABLE'=>'WebsiteCountries',
                                 'BASE'=>'SELECT c.id_country,cl.name,s.id_state,s.name AS stateName FROM WebsiteCountries w 
                                                LEFT JOIN Websites ws ON w.`id_website`=ws.`id_website`
                                                LEFT JOIN ps_country c ON w.id_country=c.id_country
                                                LEFT JOIN ps_country_lang cl ON c.id_country=cl.id_country AND cl.id_lang=ws.id_lang
                                                LEFT JOIN ps_state s ON s.id_country=c.id_country
                                                WHERE
                                                [%0%] AND [%1%]',
                                 'CONDITIONS'=>array(
                                       array(
                                             'FILTER'=>'w.id_website={%id_website%}',
                                             'TRIGGER_VAR'=>'id_website',
                                             'DISABLE_IF'=>''
                                             ),
                                       array(
                                             'FILTER'=>'s.active={%active%}',
                                             'TRIGGER_VAR'=>'active',
                                             'DISABLE_IF'=>''
                                            )
                                       )
                                 )
                           )
                     )
               );
}
?>