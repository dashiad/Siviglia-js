<?php
namespace model\web\Job;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Job/Definition.php
 CLASS:Definition
*
*
**/
use \lib\model\BaseModelDefinition;
use \model\web\Job;

class Definition extends BaseModelDefinition
{
	 static $definition = [
               'ROLE' => 'ENTITY',
               'DEFAULT_SERIALIZER' => 'default',
               'DEFAULT_WRITE_SERIALIZER' => 'default',
               'INDEXFIELDS' => ['id_job'],
               'TABLE' => 'Job',
               'LABEL' => 'job',
               'SHORTLABEL' => 'Job',
               'CARDINALITY' => '300',
               'CARDINALITY_TYPE' => 'FIXED',
               'FIELDS' => [
                     'id_job' => [
                           'TYPE' => 'AutoIncrement',
                           'MIN' => 0,
                           'MAX' => 9999999999,
                           'LABEL' => 'id_job',
                           'SHORTLABEL' => 'id_job',
                           'DESCRIPTIVE' => 'true',
                           'ISLABEL' => true
                           ],
                     'job_id' => [
                           'TYPE' => 'String',
                           'LABEL' => 'Job ID',
                           'MINLENGTH' => 16,
                           'MAXLENGTH' => 64,
                           'DESCRIPTIVE' => 'true',
                           'SHORTLABEL' => 'tag',
                           'ISLABEL' => true,
                           ],
                     'name' => [
                           'TYPE' => 'String',
                           'MINLENGTH' => 2,
                           'MAXLENGTH' => 64,
                           'LABEL' => 'Nombre',
                           'SEARCHABLE' => 1,
                           'SHORTLABEL' => 'Nombre',
                           'DESCRIPTIVE' => 'true',
                           'ISLABEL' => true
                           ],
                     'status' =>  [
                         'TYPE'       => 'Enum',
                         'VALUES'     => [
                             Job::WAITING,
                             Job::PENDING,
                             Job::RUNNING,
                             Job::FINISHED,
                             Job::FAILED,
                         ],
                         'DEFAULT'    => Job::WAITING,
                         'LABEL'      => 'Estado',
                         'SHORTLABEL' => 'Estado',
                         'METADATA'=>[
                             "display"=>[
                                Job::WAITING=>["ICON"=>"xxx"],
                                Job::PENDING=>["ICON"=>"xxx"],
                                Job::RUNNING=>["ICON"=>"xxx"],
                                Job::FINISHED=>["ICON"=>"xxx"],
                                Job::FAILED=>["ICON"=>"xxx"]
                             ],                            
                             
                         ]
                     ],
                     'parent' => [
                         'TYPE' => 'Relationship',
                         'MODEL' => '\model\web\Job',
                         'ROLE' => 'HAS_ONE',
                         'LABEL' => 'Padre',
                         'MULTIPLICITY' => '1:N',
                         'CARDINALITY' => 1,
                         'FIELDS' => ['parent' => 'job_id'],
                     ],
                     'object' => [
                         'TYPE' => 'String',
                         'MAXLENGTH' => 16*1024*1024,
                         'LABEL' => 'object',
                         'SHORTLABEL' => 'object',
                     ],
                 ],
        	     'ALIASES'=>[
        	         'workers'=>[
        	             'TYPE'=>'InverseRelation',
        	             'MODEL'=>'\model\web\Worker',
        	             'ROLE'=>'HAS_MANY',
        	             'MULTIPLICITY'=>'1:N',
        	             'CARDINALITY'=>100,
        	             'FIELDS'=>['job_id'=>'job_id'],
        	         ],
        	         'pending_workers'=>[
        	             'TYPE'=>'InverseRelation',
        	             'MODEL'=>'\model\web\Worker',
        	             'ROLE'=>'HAS_MANY',
        	             'MULTIPLICITY'=>'1:N',
        	             'CARDINALITY'=>100,
        	             'FIELDS'=>['job_id'=>'job_id'],
        	             'CONDITIONS'=>[
        	                 [
        	                     'FILTER'=>array(
        	                         'F'=>'status',
        	                         'OP'=>'=',
        	                         'V'=>Job::PENDING
        	                     )
        	                 ]        	                 
        	             ]
        	         ]
        	   ],
               'PERMISSIONS' => [],
               'SOURCE' => [
                   'STORAGE' => [
                         'MYSQL' => [
                               'ENGINE' => 'InnoDb',
                               'CHARACTER SET' => 'utf8',
                               'COLLATE' => 'utf8_general_ci',
                               'TABLE_OPTIONS' => ['ROW_FORMAT' => 'FIXED']
                               ]
                         ]
                   ]
               ];
}