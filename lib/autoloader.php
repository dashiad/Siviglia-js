<?php
/*
     Siviglia Framework
*/

// docker run -t -i -p 80:80 -v /media:/var/www/adtopy adtopy /bin/bash
class AutoloadException extends Exception { }
function mainAutoloader($name)
{
    $parts=explode('\\',($name[0]=='\\'?substr($name,1):$name));
    switch($parts[0])
    {
        case "lib":
        {
            $testClass=null;
           /* if($parts[1]==="tests")
            {
                unset($parts[1]);
                $testClass=$name;
                $name=implode('\\',array_values($parts));
            }*/
            $fileName=PROJECTPATH.\DIRECTORY_SEPARATOR.str_replace('\\',\DIRECTORY_SEPARATOR,$name).".php";
            if(file_exists($fileName))
                include_once($fileName);
            if($testClass!==null)
                class_alias($name,$testClass);
        }break;
        case "Website":
        {
            include_once(WEBROOT.str_replace('\\',\DIRECTORY_SEPARATOR,$name).".php");
        }break;
        case "sites":
            case "output":
        {
            include_once(PROJECTPATH.implode("/",$parts).".php");
        }break;
        case "model":
        {
            include_once(LIBPATH."/model/ModelService.php");
            \lib\model\ModelService::includeClass($name);

        }break;
        default:
        {
            $p=strpos($name,"model");
            if($p===false)
                return false;
            $name=str_replace("Exception","",$name);
            $def=\lib\model\ModelService::getModelDescriptor($name);
            $fName=$parts[count($parts)-1];
            $fName=str_replace("Exception","",$fName);
            $dest=$def->getDestinationFile($fName).".php";
            $dest=realpath($dest);
            if(!is_file($dest))
            {
                //throw new AutoloadException("No se encuentra la clase $name");
                return false;
            }

            include_once($dest);
        }
    }

    //restore_error_handler();
}
spl_autoload_register("mainAutoloader");
