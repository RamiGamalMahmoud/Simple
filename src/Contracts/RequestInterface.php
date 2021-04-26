<?php

namespace Simple\Contracts;

interface RequestInterface
{
    function getSegment(int $index);
    function getSegments();
    function getRequestBody();
    function getAjaxData();
    function getPath();
    function getRequestMethod();
    function getRequestType();
}
