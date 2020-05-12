<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/Image.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");


use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    function initialize()
    {
        chmod(__DIR__."/res/tmp",0777);
        copy(__DIR__."/res/img.png",__DIR__."/res/test.png");
        if(is_file(__DIR__."/res/tmp/destFile.png"))
            unlink(__DIR__."/res/tmp/destFile.png");
        if(is_file(__DIR__."/res/tmpaa/destFile_bb.png"))
            unlink(__DIR__."/res/tmpaa/destFile_bb.png");
        if(is_dir(__DIR__."/res/tmpaa"))
            rmdir(__DIR__."/res/tmpaa");
        if(is_file(__DIR__."/res/tmp/th_destFile.jpg"))
            unlink(__DIR__."/res/tmp/th_destFile.jpg");


    }
    function testDefinition1()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile"
        ]);
        $ins->importFile(__DIR__."/res/test.png");
        $ins->save();
        $v=$ins->getValue();
        $this->assertEquals($v,"destFile.png");
        $this->assertEquals(true,is_file(__DIR__."/res/tmp/destFile.png"));
    }
    // La imagen es de 116x31
    function testDefinition2()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MINWIDTH"=>120
        ]);
        $this->expectException('\lib\model\types\ImageException');
        $this->expectExceptionCode(\lib\model\types\ImageException::ERR_TOO_SMALL);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition3()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MAXWIDTH"=>110
        ]);
        $this->expectException('\lib\model\types\ImageException');
        $this->expectExceptionCode(\lib\model\types\ImageException::ERR_TOO_WIDE);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition4()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MAXWIDTH"=>110
        ]);
        $this->expectException('\lib\model\types\ImageException');
        $this->expectExceptionCode(\lib\model\types\ImageException::ERR_TOO_WIDE);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition5()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MINHEIGHT"=>40
        ]);
        $this->expectException('\lib\model\types\ImageException');
        $this->expectExceptionCode(\lib\model\types\ImageException::ERR_TOO_SHORT);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition6()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MAXHEIGHT"=>30
        ]);
        $this->expectException('\lib\model\types\ImageException');
        $this->expectExceptionCode(\lib\model\types\ImageException::ERR_TOO_TALL);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testThumbnail()
    {
        $this->initialize();
        $ins=new \lib\model\types\Image("",[
            "TYPE"=>"Image",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "THUMBNAIL"=>[
                "KEEPASPECT"=>true,
                "WIDTH"=>50,
                "HEIGHT"=>20,
                "PREFIX"=>"th_"
            ]
        ]);
        $ins->importFile(__DIR__."/res/test.png");
        $ins->save();
        $this->assertEquals(true,is_file(__DIR__."/res/tmp/th_destFile.jpg"));
    }

}
