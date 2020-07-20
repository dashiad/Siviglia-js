<?php
namespace model\web\Lang;
// Exception Class

class translationsException extends \lib\model\BaseException
{
}

/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations/translations.php
  CLASS:translations
*
*
**/

class translations extends \lib\model\BaseModel
{
    function save($serializer=null)
    {
        if($this->id_translation)
        {
            if($this->dirty==0)
                $this->dirty=1;
            else
                $this->dirty=0;
        }
        parent::save();
    }
}
?>