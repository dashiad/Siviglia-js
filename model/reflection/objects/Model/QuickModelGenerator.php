<?php
namespace model\reflection\Model;
class QuickModelGenerator
{
  static function createFromQuick($name,$layer,$quickDef)
  {
        $objectName=$name;        
        $table=$name;
        
        $instance=new \model\reflection\Model($name,$layer);
            
        // Se crea automaticamente un campo id
        $fieldIndex="id_".strtolower($objectName);
        $instance->indexFields=array($fieldIndex);
        $instance->fields[$fieldIndex]=\model\reflection\Model\Field::createField($fieldIndex,$instance,array("TYPE"=>"UUID"));

        for($k=0;$k<count($quickDef);$k++)
        {
            $isDigit=0;
            $fName=$quickDef[$k];
            if(strpos($fName,"@"))
            {
                $parts=explode("@",$fName);
                $relFields=explode(",",trim($parts[1]));
                $targetObj=substr($parts[0],1);
                $instance->addField($parts[0],\model\reflection\Model\Relationship\Relationship::createRelation($targetObj,$instance,$targetObj,$relFields));
                continue;
            }
           if($quickDef[$k][0]=='#')
           {
                $rfname=substr($fName,1);
                $instance->addField($rfname,\model\reflection\Model\Field\Field::createField($rfname,$instance,array("TYPE"=>"Integer")));
           }
           else
                $instance->addField($fName,\model\reflection\Model\Field\Field::createField($fName,$instance,array("TYPE"=>"String","MAXLENGTH"=>45)));
         }
                        
         $instance->acls=\model\reflection\Permissions\ModelPermissionsDefinition::getDefaultAcls($objectName,$layer);
         // Setup de permisos, con un array vacio.
         $instance->modelPermissions=new \model\reflection\Permissions\ModelPermissionsDefinition($instance,array());
         $instance->initialize($instance->getDefinition());
         return $instance;           
  }
}

?>
