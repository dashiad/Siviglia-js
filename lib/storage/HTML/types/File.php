<?php namespace lib\storage\HTML\types;

  class File extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())return [$name=>$type->getValue()];
		  return [$name=>""];
      }
      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          switch($value["error"])
          {
          case UPLOAD_ERR_PARTIAL: // error 3:
            {
                throw new FileException(FileException::ERR_UPLOAD_ERR_PARTIAL);
            }break;
          case UPLOAD_ERR_INI_SIZE: // error 7
          {
                throw new FileException(FileException::ERR_UPLOAD_ERR_INI_SIZE);
          }break;
          case UPLOAD_ERR_FORM_SIZE:
              {
                  throw new FileException(FileException::ERR_UPLOAD_ERR_FORM_SIZE);
              }break;
          case UPLOAD_ERR_CANT_WRITE:
              {
                  throw new FileException(FileException::ERR_UPLOAD_ERR_CANT_WRITE);
              }break;
          }

          if($value["error"]==UPLOAD_ERR_NO_FILE ) // No file uploaded
              return;

          // TODO : check error en $value["error"]
          $type->setUploadedFilename($value["name"]);
          $type->setValue($value["tmp_name"]);

      }
  }
