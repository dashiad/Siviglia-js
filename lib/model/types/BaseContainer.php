<?php


namespace lib\model\types;

class BaseContainerException extends \lib\model\BaseException {
    const ERR_NO_PARENT=101;
    const ERR_PATH_NOT_FOUND=102;
    const ERR_UNMATCHED_BRACE=103;
    const TXT_NO_PARENT="Current object has no parent";
    const TXT_PATH_NOT_FOUND="Path [%path%] not found";
    const TXT_UNMATCHED_BRACE="Unmatched brace in path [%path%]";
}

abstract class BaseContainer extends BaseType
{

    var $tempContext;
    function getPath($path=[])
    {
        try {
            $current='';
            $nBraces=0;
            $n=strlen($path);
            $i=0;
            $matches=[];
            while($i<$n)
            {
                $c=$path[$i];
                if($c=='/')
                {
                    if($nBraces==0)
                    {
                        if(strlen($current)>0) {
                            $matches[] = $current;
                            $current='';
                        }
                    }
                    else
                        $current.=$c;
                }
                else
                {
                    if($c=='{')
                        $nBraces++;
                    if($c=='}')
                        $nBraces--;
                    $current.=$c;
                }
                $i++;
            }
            if($nBraces>0)
                throw new BaseContainerException(BaseContainerException::ERR_UNMATCHED_BRACE,["path"=>$path],$this);
            $matches[]=$current;
            // Los paths son del tipo:
            //  /a/b/c/{/a/b/c}
            $parts = $matches;
            $pathLength = count($parts);
            return $this->__getPath($parts, 0, $pathLength);
        }catch(\Exception $e) {
            throw new BaseContainerException(BaseContainerException::ERR_PATH_NOT_FOUND,["path"=>$path],$this);
            }
    }



    function __getPath($path,$index,$pathLength=-1)
    {
        $current = $index;
        $next = $index + 1;
        if($path[$index]=="..")
        {
            if($this->parent===null)
                throw new BaseContainerException(BaseContainerException::ERR_NO_PARENT,null,$this);
            return $this->parent->__getPath($path, $next, $pathLength);
        }
        if ($next == $pathLength)
            return $this->__getPathProperty($path[$current],"value");
        $ref = $this->__getPathProperty($path[$current],"reference");
        if ($ref === null) {
            throw new BaseContainerException(BaseContainerException::ERR_PATH_NOT_FOUND, array("path" => "/" . implode("/", $path), "index" => $next),$this);
        }
        return $ref->__getPath($path, $next, $pathLength);
    }
    function __getPathProperty($pathProperty,$mode)
    {
        if($pathProperty[0]=="{")
        {
            $pathProperty=substr($pathProperty,1,-1);
            $pathProperty=$this->getPath($pathProperty);
        }
        if($mode=="reference")
            return $this->{"*".$pathProperty};
        return $this->{$pathProperty};
    }

}
