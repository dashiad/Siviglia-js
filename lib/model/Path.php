<?php


namespace lib\model;


class PathObjectException extends \lib\model\BaseException {
    const ERR_PATH_NOT_FOUND=1;
    const ERR_NO_CONTEXT=2;
}

class ContextStack
{
    function __construct()
    {
        $this->contextRoots = [];
    }

    function addContext($handler)
    {
        $prefix = $handler->getPrefix();
        if ($prefix === "")
            $prefix = "/";
        $this->contextRoots[$prefix] = $handler;
        $handler->setStack($this);
    }

    function removeContext($handler)
    {
        $prefix = $handler->getPrefix();
        if ($prefix === "")
            $prefix = "/";
        if (!$this->hasPrefix($prefix))
            throw new PathObjectException(PathObjectException::ERR_NO_CONTEXT, ["context" => "$prefix"]);
        $ctx = $this->contextRoots[$prefix];
        $this->contextRoots[$prefix] = null;
    }

    function getContext($prefix)
    {
        if ($this->contextRoots[$prefix] != "undefined")
            return $this->contextRoots[$prefix];
        throw new PathObjectException(PathObjectException::ERR_NO_CONTEXT, ["context" => "$prefix"]);
    }

    function getRoot($str)
    {
        $prefix = substr($str, 0, 1);
        $ctx = $this->getContext($prefix);
        return $ctx->getRoot();
    }

    function hasPrefix($char)
    {
        return isset($this->contextRoots[$char]);
    }

    function getCursor($prefix)
    {
        $cursor = new BaseCursor($this->contextRoots[$prefix]->getRoot());
        $cursor->setPrefix($prefix);
        return $cursor;
    }

    function getCopy()
    {
        $newContext = new ContextStack();
        foreach ($this->contextRoots as $k => $v)
            $newContext->addContext($v);
        return $newContext;

    }
}
class Context
{
    var $prefix;
    var $stack;
    function __construct($prefix,$stack)
    {
        $this->prefix=$prefix;
        $this->stack=$stack;
        if(isset($stack) && $stack!==null)
            $stack->addContext($this);
    }

    function getPrefix(){return $this->prefix;}
    function setStack($stack){
        $this->stack=$stack;
    }
}
class BaseCursor
{
    var $objRoot;
    var $pathStack;
    var $__lastTyped;
    var $prefix;
    var $pointer;

    function __construct($objRoot)
    {
        $this->objRoot = $objRoot;
        $this->pathStack = [];
        $this->__lastTyped = false;
        $this->prefix = null;
        $this->pointer = null;
        $this->reset();
    }

    function setPrefix($p)
    {
        $this->prefix = $p;
    }

    function getPrefix()
    {
        return $this->prefix;
    }

    function reset()
    {
        $this->__lastTyped = null;
        $this->pointer = $this->objRoot;
    }

    function moveTo($spec)
    {
        $this->__lastTyped = false;
        if($spec[0]=="*")
        {
            $this->__lastTyped=true;
            $spec=substr($spec,1);
        }
        if ($spec === "..") {
            $cVal = $this->pointer->getParent();
            $this->pointer = $cVal;
            return $cVal;
        } else {
            $v = null;
            if(is_a($this->pointer,'\lib\model\BaseTypedObject') && !is_a($this->pointer,'\lib\model\ModelBaseRelation'))
            {
                $field=$this->pointer->__getField($spec,true);
                if($field->isRelation())
                    $v=$this->pointer->{$spec};
                else
                    $v=$this->pointer->{"*".$spec};
            }
            else {
                if (is_array($this->pointer)) {
                    if (!isset($this->pointer[$spec]))
                        throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND, array("path" => $spec));
                    $v = $this->pointer[$spec];
                } else {
                    if (is_object($this->pointer)) {
                        if(is_a($this->pointer,'\lib\model\types\BaseContainer') &&
                            $spec!=="[[KEYS]]" && $spec!=="[[SOURCE]]")
                        {
                            $v=$this->pointer->{"*".$spec};
                        }
                        else
                            $v = $this->pointer->{$spec};
                        if (!isset($v) || $v === null) {
                            if ($this->pointer instanceof \ArrayAccess) {
                                $v = $this->pointer[$spec];
                                if (!isset($v))
                                    throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND, array("path" => $spec));
                            } else
                                throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND, array("path" => $spec));
                        }
                    }
                }
            }
            if (!isset($v))
                throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND, ["path" => "$spec"]);
            $this->pointer = $v;
        }
    }

    function getValue()
    {
        if(is_a($this->pointer,'\lib\model\types\BaseType'))
            return $this->pointer->getReference();
        return $this->pointer;
    }
}

class BaseObjectContext extends Context{
    var $objRoot;
    function __construct($objRoot,$prefix,$stack)
    {
        $this->objRoot=$objRoot;
        Context::__construct($prefix,$stack);
    }

    function getRoot(){
        return $this->objRoot;
    }
    function getCursor()
    {
        return new BaseObjectContainerCursor($this->objRoot);
    }
}
class PathResolver
{
    var $contexts;
    var $path;
    var $valid;
    var $cursors;
    var $lastValue;
    function __construct($contexts,$path)
    {
        $this->contexts = $contexts;

        if(($path[1]==="*" || $path[1]==="@") && $path[0]==="/")
            $path=substr($path,1);
        $this->path=$path;
        $this->cursors=[];
        $this->valid=false;
        $this->lastValue=null;
    }
    function buildTree($str)
    {
        $prefix=$str[0];
        $len=strlen($str);
        $stack=[];
        $cad="";
        for($k=1;$k<$len;$k++)
        {
            if($str[$k]=="%" && $str[$k+1]=="}")
            {
                if($cad!="") {
                    $stack[]=
                        ["str"=> $cad,
                            "type"=>"pathElement"];
                    $cad="";
                }
                $len=$k+2;
                break;
            }
            if($str[$k]=="{" && $str[$k+1]=="%")
            {
                if($cad!=="")
                {
                    $stack[]=["type"=>"pathElement","str"=>$cad];
                }
                $res=$this->buildTree(substr($str,$k+2));
                $stack[]=["prefix"=>$prefix,"type"=>"subpath","subtype"=>"static","components"=>$res["stack"]];
                $k+=($res["len"]+1);
                $cad="";
                continue;
            }
            $cad.=$str[$k];
        }

        if($cad!="")
        {
            $stack[]=["type"=>"pathElement","str"=>$cad];
        }
        $res=[];
        for($k=0;$k<count($stack);$k++)
        {
            if($stack[$k]["type"]!=="pathElement")
            {
                $res[]=$stack[$k];
                continue;
            }
            $parts=explode("/",$stack[$k]["str"]);
            for($j=0;$j<count($parts);$j++)
            {
                if($parts[$j]=="")continue;
                $res[]=["prefix"=>$prefix,"type"=>"pathElement","str"=>$parts[$j]];
            }
        }
        return ["len"=>$len,"stack"=>$res];
    }


    function isValid()
    {
        return $this->valid;
    }
    function getPath()
    {
        $p=$this->path[0];
        if($this->contexts->hasPrefix($p))
        {
            $result = $this->buildTree($this->path);
            $this->stack=$result["stack"];
            $this->valid = true;
            try {
                $newVal = $this->parse($this->stack);
            }catch(\Exception $e)
            {
                $this->valid=false;
                $newVal=null;
                throw $e;
            }
        }
        else
        {
            $this->valid=true;
            $newVal=$this->path;
        }
        $this->lastValue=$newVal;

        return $newVal;
    }
    function getValue(){return $this->lastValue;}
    function parse($pathParts)
    {
        $cursor=$this->contexts->getCursor($pathParts[0]["prefix"]);
        $this->cursors[]=$cursor;
        for($k=0;$k<count($pathParts) && $this->valid ;$k++)
        {
            $p=$pathParts[$k];
            switch($p["type"])
            {
                case "pathElement":
                    {
                        try {
                            $cursor->moveTo($p["str"]);
                        }catch(\Exception $e)
                        {
                            $this->valid=false;
                            throw $e;
                        }
                    }break;
                case "subpath":
                    {
                        $val=$this->parse($p["components"],$p["subtype"]=="static"?false:true);
                        if($this->valid) {
                            $cursor->moveTo($val);
                        }
                    }break;
            }
        }
        return $cursor->getValue();
    }
}
