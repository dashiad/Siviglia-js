<?php
namespace model\reflection\Html\views;
class ListWidget extends \model\reflection\base\ConfiguredObject
{
    var $generating=false;
    function __construct($name,$parentModel,$parentDs)
    {
        $this->parentDs=$parentDs;
        $filePath="/html/views/";
        parent::__construct($name,$parentModel,'', $filePath, "datasourceTemplate", null, false,".wid");
    }

    function initialize($definition=null)
    {
        $this->parentDs->setWidget("html",$this);
    }

    function generateCode($isadmin,$asSubDs=false,$subDsIterator=null)
    {
        if($this->generating)
            return "";
        $this->generating=true;
        if($asSubDs==false && $this->definition["ROLE"]=="MxNlist")
            return;
        $phpCode="";
        if(!$asSubDs)
        {
        $phpCode=<<<'TEMPLATE'
            $params=Registry::$registry["PAGE"];
            $serializer=\Registry::getService("storage")->getSerializerByName('{%layer%}');                        
TEMPLATE;
            $widgetCode='[*LIST_DS({"currentPage":"$currentPage","object":"{%object%}","dsName":"{%dsName%}","serializer":"$serializer","params":"$params","iterator":"&$iterator"})]'."\n";
            $subDsIterator="iterator";
        }
        else
        {
                $widgetCode='[*LIST_IT({"currentPage":"$currentPage","iterator":"&$'.$subDsIterator.'","subModel":"&$curModel"})]'."\n";
                $subDsIterator="curModel";
        }

        $widgetCode.= <<<'WIDGET'
                [_HEADER]
                    [_TITLE]Titulo de la lista[#]
                    [_DESCRIPTION]Descripcion de la lista[#]
                [#]
                [_LISTING]
                    [_COLUMNHEADERS]
{%headers%}        
                    [#]
                    [_ROWS]
{%columns%}
                    [#]
                    [_LISTINGFOOTER]
                    [#]
                [#]     
           [#]
WIDGET;

        // Se buscan todos los objetos que tenemos en metadata.
        $def=$this->parentDs->getDefinition();
        $metadata=$def["FIELDS"];
        if(!$metadata)
            $metadata=$def["PARAMS"];
        $headerCad="";
        $columnCad="";
        foreach($metadata as $fName=>$fDef)
        {
            $def=\lib\model\types\TypeFactory::getObjectField($this->parentModel,$fName);
            $type=\lib\model\types\TypeFactory::getType(["fieldName"=>$fName,"path"=>"/"],$def,null);
            $typeDef=$type->getDefinition();
            $typeClass=get_class($type);
            $pos=strrpos($typeClass,"\\");
            $className=substr($typeClass,$pos+1);
            $headerCad.="\t\t\t\t\t\t[_COLUMN][_LABEL]".(isset($typeDef["LABEL"])?$typeDef["LABEL"]:$fName)."[#][#]\n";
            $columnCad.="\t\t\t\t\t\t[_ROW][_VALUE][*:/types/".$className."({\"name\":\"".$fName."\",\"model\":\"\$".$subDsIterator."\"})][#][#][#]\n";
        }
        if($isadmin && $this->parentDs->haveIndexes())
        {
            $headerCad.="\t\t\t\t\t\t[_COLUMN][_LABEL][*/icons/delete][#][#][#]\n";
            $columnCad.="\t\t\t\t\t\t[_ROW][_VALUE][*/list/icons/delete({\"model\":\"\$".$subDsIterator."\",\"indexes\":[\"".implode("\",\"",$this->modelIndexes)."\"]})][#][#][#]\n";
        }
        $searchs=array("{%layer%}","{%object%}","{%dsName%}","{%headers%}","{%columns%}");

        $replaces=array($this->parentModel->getLayer(),
                        str_replace('\\','/',$this->parentModel->getClassName()),
                        $this->parentDs->getName(),
                        $headerCad,
                        $columnCad
                        );
        $code=str_replace($searchs,$replaces,"<?php\n".$phpCode."\n?>\n".$widgetCode."\n");

        $this->generating=false;
        if($asSubDs)
            return $code;
        $this->code=$code;
        return $code;

    }
    function save()
    {
        @mkdir(dirname($this->filePath),0777,true);
        file_put_contents($this->filePath,$this->code);

    }
}
