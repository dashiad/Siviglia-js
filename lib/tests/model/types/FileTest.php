<?php

namespace lib\tests\model\types;


$dirName=__DIR__."/../../../../install/config/CONFIG_test.php";
include_once($dirName);
include_once(LIBPATH."/startup.php");
include_once(LIBPATH."/autoloader.php");
include_once(PROJECTPATH."/lib/model/BaseTypedObject.php");
include_once(PROJECTPATH."/lib/model/types/File.php");
include_once(PROJECTPATH."/lib/model/types/Container.php");
include_once(PROJECTPATH."/lib/model/types/_String.php");


use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{

    function initialize()
    {
        if(!is_dir(__DIR__."/res/tmp"))
            mkdir(__DIR__."/res/tmp");
        chmod(__DIR__."/res/tmp",0777);
        copy(__DIR__."/res/img.png",__DIR__."/res/test.png");
        if(is_file(__DIR__."/res/tmp/destFile.png"))
            unlink(__DIR__."/res/tmp/destFile.png");
        if(is_file(__DIR__."/res/tmpaa/destFile_bb.png"))
            unlink(__DIR__."/res/tmpaa/destFile_bb.png");
        if(is_dir(__DIR__."/res/tmpaa"))
            rmdir(__DIR__."/res/tmpaa");

    }
    function testDefinition1()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile"
        ]);
        $ins->importFile(__DIR__."/res/test.png");
        $ins->save();
        $v=$ins->getValue();
        $this->assertEquals($v,"destFile.png");
        $this->assertEquals(true,is_file(__DIR__."/res/tmp/destFile.png"));
    }
    function testDefinition2()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
        ]);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_FILE_DOESNT_EXISTS);
        $ins->importFile(__DIR__."/res/test2.png");
    }
    function testDefinition3()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MINSIZE"=>40000
        ]);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_FILE_TOO_SMALL);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition4()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "MAXSIZE"=>1000
        ]);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_FILE_TOO_BIG);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition5()
    {

        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "EXTENSIONS"=>["jpg"]
        ]);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_INVALID_FILE);
        $ins->importFile(__DIR__."/res/test.png");
    }
    function testDefinition6()
    {
        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile",
            "AUTODELETE"=>true
        ]);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_FILE_DOESNT_EXISTS);
        $ins->importFile(__DIR__."/res/test2.png");
        $ex=is_file(__DIR__."/res/tmp/destFile.png");
        $this->assertEquals(true,$ex);
        $ins->clear();
        $ex=is_file(__DIR__."/res/tmp/destFile.png");
        $this->assertEquals(false,$ex);
    }
    function testDefinition7()
    {

        $this->initialize();
        $ins=new \lib\model\types\File("",[
            "TYPE"=>"File",
            "TARGET_FILEPATH"=>__DIR__."/res/tmp",
            "TARGET_FILENAME"=>"destFile"

        ]);
        chmod(__DIR__."/res/tmp",0000);
        $this->expectException('\lib\model\types\FileException');
        $this->expectExceptionCode(\lib\model\types\FileException::ERR_NOT_WRITABLE_PATH);
        $ins->importFile(__DIR__."/res/test.png");
        $ins->save();
    }
    function testNormalize()
    {
        $p='c:\a\b\..\d\\\\.\\e';
        $n=\lib\model\types\File::normalizePath($p);
        $this->assertEquals("c:/a/d/e",$n);
        $p='c:\a\b\..\d\\\\.\\e\..\..\s';
        $n=\lib\model\types\File::normalizePath($p);
        $this->assertEquals("c:/a/s",$n);
        $p="/a/b/../s";
        $n=\lib\model\types\File::normalizePath($p);
        $this->assertEquals("/a/s",$n);

    }
    function testContainer()
    {
        $c=new \lib\model\types\Container("",[
            "FIELDS"=>[
                "id1"=>["TYPE"=>"String"],
                "id2"=>["TYPE"=>"String"],
                "file"=>[
                    "TYPE"=>"File",
                    "TARGET_FILEPATH"=>__DIR__."/res/tmp[%id1%]",
                    "TARGET_FILENAME"=>"destFile_[%id2%]"
                ]
            ]
        ]);
        $c->id1="aa";
        $c->id2="bb";
        $c->{"*file"}->importFile(__DIR__."/res/img.png");
        $c->{"*file"}->save();
        $ex=is_file(__DIR__."/res/tmpaa/destFile_bb.png");
        $this->assertEquals(true,$ex);

    }

}
