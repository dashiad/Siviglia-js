<?php namespace lib\model\types;

 class  FileException extends BaseTypeException{
      const ERR_FILE_TOO_SMALL=100;
      const ERR_FILE_TOO_BIG=101;
      const ERR_INVALID_FILE=102;
      const ERR_NOT_WRITABLE_PATH=103;
      const ERR_FILE_DOESNT_EXISTS=105;
      const ERR_CANT_MOVE_FILE=106;
      const ERR_CANT_CREATE_DIRECTORY=107;


     const TXT_FILE_TOO_SMALL="File size [%actualsize%] is below minimum of [%fsize%]";
     const TXT_FILE_TOO_BIG="File size [%actualsize%] is above maximum of [%fsize%]";
     const TXT_INVALID_FILE="File extension [%extension%] is not allowed ([%allowed%])";
     const TXT_NOT_WRITABLE_PATH="Path [%path%] is not writable";
     const TXT_FILE_DOESNT_EXISTS="File [%fileName%] does not exist";
     const TXT_CANT_MOVE_FILE="Cant move file from [%src%] to [%dest%]";
     const TXT_CANT_CREATE_DIRECTORY="Cant create directory [%dir%]";



  }

  class File extends BaseType
  {
      var $mustCopy=false;
      var $srcFile=null;
      var $isUpload=false;
      function __construct($def,$neutralValue=null)
      {
        parent::__construct($def,$neutralValue);

        $this->setFlags(BaseType::TYPE_IS_FILE | BaseType::TYPE_REQUIRES_UPDATE_ON_NEW | BaseType::TYPE_REQUIRES_SAVE | BaseType::TYPE_NOT_MODIFIED_ON_NULL);
      }
      function setFlags($flags)
      {
          $this->flags|=$flags;
      }
      function getFlags()
      {
          return $this->flags;
      }
      // setUnserialized no marca como dirty.
      function setUnserializedValue($val)
      {
          if(isset($val) && !empty($val))
          {
              $this->value=$val;
              $this->valueSet=true;
          }
      }
      function importFile($fileName,$isUpload=false)
      {
          $this->validateImportedFile($fileName);
          $this->isUpload=$isUpload;
          $this->srcFile = $fileName;
      }
      function importUploadedFile($fileName)
      {
          $this->importFile($fileName,true);
      }

      function _setValue($val)
      {
          // Ojo..En caso de que estemos en un upload de HTML, este val es el fichero temporal PHP.Esto es asi, porque este tipo de dato,
          // usa el contexto para decidir el nombre y el path de fichero.Y ese contexto no estara listo hasta que se haya hecho save() del
          // modelo.Por ejemplo, esto ocurre cuando en el nombre del fichero debe ir un id autogenerado.
          if(isset($this->definition["TARGET_FILEPATH"]))
              $val=str_replace($this->definition["TARGET_FILEPATH"],"",$val);
          $this->value=$val;
      }

      function _equals($value)
      {
          if($this->value===$value)
              return true;
          $normalizedV=File::normalizePath($value);
          $normalizedVV=File::normalizePath($this->value);
          if(isset($this->definition["TARGET_FILEPATH"]))
          {
              $normalizedBase=File::normalizePath($this->definition["TARGET_FILEPATH"]);
              $normalizedV=str_replace($normalizedBase,"",$normalizedV);
              $normalizedVV=str_replace($normalizedBase,"",$normalizedVV);
          }
          return $normalizedV==$normalizedVV;
      }

      function _getValue()
      {
          return $this->value;
      }
      function validateImportedFile($fileName)
      {
          if(!is_file($fileName))
          {
              throw new FileException(FileException::ERR_FILE_DOESNT_EXISTS,array("path"=>$fileName),$this);
          }
          $fsize=filesize($fileName);
          if(isset($this->definition["MINSIZE"]) && $this->definition["MINSIZE"] > $fsize)
              throw new FileException(FileException::ERR_FILE_TOO_SMALL,array("minsize"=>$this->definition["MINSIZE"],"actualsize"=>$fsize),$this);

          if(isset($this->definition["MAXSIZE"]) && $this->definition["MAXSIZE"] < $fsize)
              throw new FileException(FileException::ERR_FILE_TOO_BIG,array("minsize"=>$this->definition["MAXSIZE"],"actualsize"=>$fsize),$this);


          if(isset($this->definition["EXTENSIONS"]))
          {
              if(!is_array($this->definition["EXTENSIONS"]))
                  $allowedExtensions=array($this->definition["EXTENSIONS"]);
              else
                  $allowedExtensions=$this->definition["EXTENSIONS"];

              $extension=array_pop(explode(".",$fileName));

              if(!in_array($extension,array_map(function($i){return strtolower($i);},$allowedExtensions)))
              {
                  throw new FileException(FileException::ERR_INVALID_FILE,array("extension"=>$extension,"allowed"=>implode(",",$allowedExtensions)),$this);
              }
          }
          return true;
      }

      // La validacion de ficheros solo se hace a traves de validateImportedFile.
      function _validate($value)
      {
            return true;
      }
      static function normalizePath($path)
      {
          $path=str_replace('\\','/',$path);
          $parts=explode("/",$path);
          $res=[];
          $n=count($parts);
          for($k=0;$k<$n;$k++)
          {
              if($parts[$k]=="" || $parts[$k]==".")
                continue;
              if($parts[$k]=="..")
                  array_pop($res);
              else
                  $res[]=$parts[$k];

          }
          $sP=implode("/",$res);
          if($parts[0]=="")
              $sP="/".$sP;
          return $sP;
      }

      function calculateFinalPath($filename)
      {
          $filePath=$this->definition["TARGET_FILEPATH"];
          // Si esta establecido TARGET_FILENAME, no especifica una extension, asi que hay que copiarla de $filename.

          if(isset($this->definition["TARGET_FILENAME"]))
          {
                 $filePath.="/".$this->definition["TARGET_FILENAME"].".".(array_pop(explode(".",$filename)));
          }
             else
                 $filePath.="/".$filename;
             if($this->parent!==null) {
                 return \lib\php\ParametrizableString::getParametrizedString($filePath, $this->parent->getValue());
             }
             return $filePath;
      }

      function postValidate($value)
      {
          if(!$this->hasValue())
          {
              return;
          }

          if($this->srcFile)
          {
              // Ahora hay que mover el fichero a su path final.El procedimiento para hacer esto es distinto segun si el fichero
              // ha venido via HTML (lease, move_uploaded_file), o no.Esto, tambien podria hacerse en el serializador HTML, cosa que
              // queda pendiente de analizar los pro/contra.El mecanismo seria que el serializer haga el move_uploaded_file a algun sitio,
              // y que el tipo de dato, lo vuelva a mover a su destino final.Esta complejidad es lo que no me gusta de moverlo al serializador.
              // Pero, a la vez, hacerlo en el tipo de dato (esta clase), supone chequear si se ha establecido o no cierta variable, no directamente
              // relacionada con move_uploaded_file.

              // En el metodo "save", que se ha tenido que llamar antes, se ha calculado $value.

              $destFile=$this->calculateFinalPath($this->srcFile);

              if($this->isUpload)
              {
                  if(!move_uploaded_file($this->srcFile,$destFile))
                  {
                      throw new FileException(FileException::ERR_CANT_MOVE_FILE,array("src"=>$this->srcFile,"dest"=>$destFile),$this);
                  }
              }
              else
              {
                  if(!@rename($this->srcFile,$destFile))
                      throw new FileException(FileException::ERR_CANT_MOVE_FILE,array("src"=>$this->srcFile,"dest"=>$destFile),$this);

              }

              $this->uploadedFileName=null;
              $this->srcFile=null;
          }
      }
      function getFullFilePath()
      {
          if(!$this->hasOwnValue())
              return null;
          if(isset($this->definition["TARGET_FILEPATH"]))
              return $this->definition["TARGET_FILEPATH"]."/".$this->value;
          return $this->value;

      }

      function clear()
      {
          if($this->hasOwnValue())
          {
              // Tenia valor, pero ahora se pone a null=>puede haber que borrar el fichero.
              if(!isset($this->definition["AUTODELETE"]) || $this->definition["AUTODELETE"]==true)
              {
                 if(file_exists($this->value))
                      @unlink($this->value);
              }
          }
          $this->srcFile=null;
          $this->isUpload=null;
          parent::clear();
      }

      function save()
      {
          if($this->srcFile)
          {
              // Ahora hay que mover el fichero a su path final.El procedimiento para hacer esto es distinto segun si el fichero
              // ha venido via HTML (lease, move_uploaded_file), o no.Esto, tambien podria hacerse en el serializador HTML, cosa que
              // queda pendiente de analizar los pro/contra.El mecanismo seria que el serializer haga el move_uploaded_file a algun sitio,
              // y que el tipo de dato, lo vuelva a mover a su destino final.Esta complejidad es lo que no me gusta de moverlo al serializador.
              // Pero, a la vez, hacerlo en el tipo de dato (esta clase), supone chequear si se ha establecido o no cierta variable, no directamente
              // relacionada con move_uploaded_file.
              if($this->isUpload)

                  $filePath=$this->calculateFinalPath($this->srcFile);
              else
                  $filePath=$this->calculateFinalPath(basename($this->srcFile));
              $filePath=File::normalizePath($filePath);
              $destDir=dirname($filePath);
              if(!is_dir($destDir))
              {
                  if(!@mkdir($destDir,0777,true))
                      throw new FileException(FileException::ERR_CANT_CREATE_DIRECTORY,array("dir"=>$destDir),$this);
              }
              if(!is_writable(dirname($filePath)))
                   throw new FileException(FileException::ERR_NOT_WRITABLE_PATH,array("path"=>$filePath),$this);

              // Establecemos de nuevo el valor.
              // Por defecto, se va a almacenar un path relativo al projectPath

              if(!isset($this->definition["PATHTYPE"]) || $this->definition["PATHTYPE"]=="RELATIVE")
              {
                  $nrm=File::normalizePath($this->definition["TARGET_FILEPATH"]);
                  $filePath=trim(str_replace($nrm,"",$filePath),"/");
              }
              $this->valueSet=true;
              $this->value=$filePath;
          }
          $this->postValidate(null);
      }

      function _copy($type)
      {
          if($type->hasValue())
          {
              $remVal=$type->getValue();
              if($this->hasOwnValue() && $remVal==$this->value)
                  return;

              $this->valueSet=true;
              $this->value=$type->getValue();
              $this->srcFile=$type->srcFile;
              $this->isUpload=$type->isUpload;


          }
          else
          {
              if(!$this->hasValue())
                  return;
              $this->valueSet=false;
              $this->value=null;
              $this->srcFile=null;
              $this->isUpload=false;
          }

      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/File.php");
          return '\model\reflection\Types\meta\File';
      }

  }
