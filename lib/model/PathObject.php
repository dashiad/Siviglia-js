<?php


namespace lib\model;
include_once(__DIR__."/Path.php");

interface PathObject
{
    function getPath($path,$contextStack=null);
}