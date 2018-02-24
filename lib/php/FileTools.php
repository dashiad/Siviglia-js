<?php
namespace lib\php;
class FileTools
{
   static function recurse_copy($src,$dst) { 
       $dir = opendir($src); 
       @mkdir($dst); 
       while(false !== ( $file = readdir($dir)) ) { 
           if (( $file != '.' ) && ( $file != '..' )) { 
               if ( is_dir($src . '/' . $file) ) { 
                   recurse_copy($src . '/' . $file,$dst . '/' . $file); 
               } 
               else { 
                   copy($src . '/' . $file,$dst . '/' . $file); 
               } 
           } 
       } 
       closedir($dir); 
   }
   private static function __getFilesInDirectory($dir,$asNested,$relativePath="",$extensions=null,$relative=true)
   {
       $result=array();
       $dirObj = new \DirectoryIterator($dir);
       foreach ($dirObj as $fileinfo) {
           $fName=$fileinfo->getFilename();
           if($fileinfo->isDot())
               continue;
           if ($fileinfo->isDir()) {
               $r=FileTools::__getFilesInDirectory($dir."/".$fName,$asNested,$relativePath.$fName."/",$extensions,$relative);
               if($asNested)
                   $result[$fName]=$r;
               else
                   $result=array_merge($result,$r);
           }
           else
           {
               if($extensions!=null)
               {
                   if(!in_array($fileinfo->getExtension(),$extensions))
                       continue;
               }
               if($asNested)
               {
                   $result[]=$fName;
               }
               else
               {
                   $fPath=$relativePath.$fName;
                   if(!$relative)
                       $fPath=$dir.$fPath;
                   $result[]=$fPath;
               }
           }
       }
       return $result;

   }
   static function getFilesInDirectory($dir,$asNested=true,$extensions=null,$relative=true)
   {
       if(substr($dir,-1,1)!=="/")
           $dir.="/";
       return  FileTools::__getFilesInDirectory($dir,$asNested,"",$extensions,$relative);
   }
}
