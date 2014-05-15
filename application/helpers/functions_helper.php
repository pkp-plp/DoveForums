<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('button'))
{
    function button($url=NULL, $string, $extra=NULL)
    {
        return anchor ( site_url($url), $string, $extra );
    }
}