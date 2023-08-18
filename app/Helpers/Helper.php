<?php

namespace App\Helpers;

class Helper
{
public static function prepareApiResponse($message, $code, $data = array())
{
    return array("message" => $message, "status" => $code, "data" => $data);
}
}
