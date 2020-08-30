<?php

namespace Simple\Core;

interface IRequest
{
    function __construct($parh = '');
    function getSegment(int $index);
    function getSegments();
    function getRequestBody();
    function getAjaxData();
    function getPath();
    function getRequestMethod();
}