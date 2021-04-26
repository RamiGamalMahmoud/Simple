<?php

namespace Simple\Contracts;

interface RequestInterface
{
    function __construct($parh = '');
    function getSegment(int $index);
    function getSegments();
    function getRequestBody();
    function getAjaxData();
    function getPath();
    function getRequestMethod();
    function getRequestType();
}
