<?php
namespace lib\model\types;
include_once(LIBPATH."/model/types/File.php");
 class  ImageException extends FileException{
      const ERR_NOT_AN_IMAGE=120;
      const ERR_TOO_SMALL=121;
      const ERR_TOO_WIDE=122;
      const ERR_TOO_SHORT=123;
      const ERR_TOO_TALL=124;
  }

class Image extends File
{
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        if(!$def["EXTENSIONS"])
           $def["EXTENSIONS"]=array("jpg","gif","png");
        parent::__construct($name,$def,$parentType,$value,$validationMode);
    }
    function validateImportedFile($value)
    {
        parent::validateImportedFile($value);

        // Se realizan las comprobaciones
        $size=getImageSize($value);
        $minWidth=isset($this->__definition["MINWIDTH"]);
        $minHeight=isset($this->__definition["MINHEIGHT"]);
        $maxWidth=isset($this->__definition["MAXWIDTH"]);
        $maxHeight=isset($this->__definition["MAXHEIGHT"]);

        if($minWidth && $size[0] < $this->__definition["MINWIDTH"])
            throw new ImageException(ImageException::ERR_TOO_SMALL,array("min"=>$this->__definition["MINWIDTH"],"current"=>$size[0]),$this);
        if($maxWidth && $size[0] > $this->__definition["MAXWIDTH"])
            throw new ImageException(ImageException::ERR_TOO_WIDE,array("max"=>$this->__definition["MAXWIDTH"],"current"=>$size[0]),$this);
        if($minHeight && $size[1] < $this->__definition["MINHEIGHT"])
            throw new ImageException(ImageException::ERR_TOO_SHORT,array("min"=>$this->__definition["MINHEIGHT"],"current"=>$size[1]),$this);
        if($maxHeight && $size[1] > $this->__definition["MAXHEIGHT"])
            throw new ImageException(ImageException::ERR_TOO_TALL,array("max"=>$this->__definition["MAXHEIGHT"],"current"=>$size[1]),$this);
    }

    function save()
    {

        parent::save();
        if(isset($this->__definition["THUMBNAIL"]))
          $this->makeThumbNail($this->__definition["THUMBNAIL"]);
        if(isset($this->__definition["WATERMARK"]))
          $this->addWatermark($this->__definition["WATERMARK"]);

    }

    public function makeThumbNail($def)
    {
        if(!$this->__hasOwnValue())
            return;

        $srcImage=$this->getFullFilePath();
        $sizes=getimagesize($srcImage);
        if(!$sizes)
            return;

        $width=$sizes[0];
        $height=$sizes[1];
        $mode=0; // 0: portrait, 1:landscape
        if($width>$height)
            $mode=1;

        $dwidth=isset($def["WIDTH"])?$def["WIDTH"]:100;
        $dheight=isset($def["HEIGHT"])?$def["HEIGHT"]:100;

        if(!isset($def["KEEPASPECT"]) || $def["KEEPASPECT"])
        {
             if($dwidth)
                 $diffwidth=abs($dwidth-$width);

             if($dheight)
                 $diffheight=abs($dheight-$height);

             if($diffheight && ($diffheight < $diffwidth || !$dwidth))
                     $dwidth=$width*($dheight/$height);

             if($diffwidth && ($diffwidth < $diffheight || !$dheight))
                    $dheight=($dwidth*$height)/$width;
        }
        // apertura de imagen..El destino va a ser siempre jpg.Hay que saber que tipo de imagen
        // exacta es el origen.
        $type=image_type_to_extension($sizes[2],false);
        $funcName="imagecreatefrom".$type;

        $img=$funcName($srcImage);
        if(!$img)
            return;
        // se crea la imagen destino.
        $destImg=imagecreatetruecolor(intval($dwidth),intval($dheight));
        imagecopyresampled($destImg,$img,0,0,0,0,$dwidth,$dheight,$width,$height);
        $fileName=basename($srcImage);
        $suffix=$def["PREFIX"];
        if(!$suffix)
            $suffix="th_";
        $quality=$def["QUALITY"];
        if(!$quality)
            $quality=85;
        // Se cambia la extension
        $parts=explode(".",$fileName);
        array_pop($parts);
        $parts[]="jpg";
        $fileName=implode(".",$parts);
        imagejpeg($destImg,dirname($srcImage)."/".$suffix.$fileName,$quality);
    }

    public function addWatermark($def)
    {
        if(!$this->__hasOwnValue())
            return;
        $dstFile=$this->value;
        $wtSize=getimagesize($def["FILE"]); // Fichero del watermark.
        $srcSize=getimagesize($dstFile);
        $quality=85;
        switch($def["POSITION"])
        {
            case "NW":{
                $dstx=$def["OFFSETX"];
                $dsty=$def["OFFSETY"];
            }break;
        case "NE":
            {
                $dstx=$srcSize[0]-$wtSize[0]-$def["OFFSETX"];
                $dsty=$def["OFFSETY"];
            }break;
        case "SE":
            {
                $dstx=$srcSize[0]-$wtSize[0]-$def["OFFSETX"];
                $dsty=$srcSize[1]-$wtSize[1]-$def["OFFSETY"];
            }break;
        case "SW":
            {
                $dstx=$def["OFFSETX"];
                $dsty=$srcSize[1]-$wtSize[1]-$def["OFFSETY"];
            }break;
        case "CENTER":
            {
                $dstx=intval(($srcSize[0]-$wtSize[0])/2);
                $dsty=intval(($srcSize[1]-$wtSize[1])/2);
            }break;
        }
        $wttype=image_type_to_extension($wtSize[2],false);
        $funcName="imagecreatefrom".$wttype;
        $wtimg=$funcName($def["FILE"]);

        $srctype=image_type_to_extension($srcSize[2],false);
        $funcName="imagecreatefrom".$srctype;
        $srcimg=$funcName($dstFile);
        imagecopyresampled($srcimg,$wtimg,$dstx,$dsty,0,0,$wtSize[0],$wtSize[1],$wtSize[0],$wtSize[1]);
        imagejpeg($srcimg,$dstFile,$quality);
    }
    function hasThumbnail()
    {
        return isset($this->__definition["THUMBNAIL"]);
    }
    function getThumbnailWidth()
    {
        return $this->__definition["THUMBNAIL"]["WIDTH"];
    }
    function getThumbnailHeight()
    {
        return $this->__definition["THUMBNAIL"]["HEIGHT"];
    }
    function hasDescription()
    {
        return isset($this->__definition["DESCRIPTION"]);
    }
    function getDescription()
    {
        return $this->__definition["DESCRIPTION"];
    }
    function getThumbnailPath()
    {
        if($this->__hasValue())
        {
            $prefix=isset($this->__definition["THUMBNAIL"]["PREFIX"])?$this->__definition["THUMBNAIL"]["PREFIX"]:"th_";
            $info=pathinfo($this->value);
            return $info["dirname"]."/".$prefix.$info['filename'].".".$info["extension"];
        }
        return '';
    }


}
