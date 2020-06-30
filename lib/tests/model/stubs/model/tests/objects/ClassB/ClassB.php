<?php
/**
 * Class ClassA
 * @package model\tests\objects
 *  (c) Smartclip
 */


namespace model\tests;


class ClassB extends \lib\model\BaseModel
{
    protected $cbOneCalled=false;
    protected $cbTwoCalled=false;
    protected $testCalled=false;

    function callback_one($target)
    {
        $this->cbOneCalled=$target->one;
    }
    function callback_two($target)
    {
        $this->cbTwoCalled=$target->two;
    }
    function test_ok($target)
    {
        return $target->one=="ffff";
    }
    function getCbOneCalled(){
        return $this->cbOneCalled;
    }
    function getCbTwoCalled(){return $this->cbTwoCalled;}
    function getCTestCalled(){return $this->testCalled;}
}
