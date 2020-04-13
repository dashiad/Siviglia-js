<?php
namespace model\reflection\Permissions;
class PermissionRequirementsDefinition
{
    var $stateDef;
    var $definition;

    function __construct($definition)
    {
        if(is_array($definition))
        {

            if(\lib\php\ArrayTools::isAssociative($definition))
            {
                if($definition["STATES"])
                {
                    $this->stateDef=new \model\reflection\StateRequirementDefinition($definition["STATES"],'\lib\reflection\PermissionRequirementsDefinition');
                }
            }
            else
            {
                if(count($definition)==0) // "PERMISSIONS"=>array()
                    $this->definition=array(["TYPE"=>"Public"]);
                else
                {
                    $this->definition=$definition;
                }
            }
        }
        else
        {

            if(!$definition)
                $this->definition=array(["TYPE"=>"Public"]);
            else
            {
                $this->definition=array($definition);
            }
        }
    }

    static function create($def)
    {
        $obj= new PermissionRequirementsDefinition($def);
        return $obj;
    }
    function isStated()
    {
        return $this->stateDef!=NULL;
    }
    function getRequiredPermissionsForState($state)
    {
        if( !$this->isStated() )
            return $this->definition;
        return $this->stateDef->getObjectForState($state);
    }

    function isJustPublic()
    {
        return ($this->definition==["TYPE"=>"Public"] || $this->definition==array(["TYPE"=>"Public"]) );
    }
    function getDefinition()
    {
        if($this->stateDef)
            return $this->stateDef->getDefinition();
        if( !is_array($this->definition) )
            return array($this->definition);
        return $this->definition;
    }
    function getRequiredPermissions($model)
    {
        if($this->stateDef)
        {
            $state=$model->getState();
            return $this->stateDef[$state];
        }
        return $this->definition;
    }
}
