<?php
if (!function_exists('phoneMask')) {
    function phoneMask($phone) {
        preg_match('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', $phone, $matches);
        return "+{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}-{$matches[5]}";
    }
}
if (!function_exists('validPhone')) {
    function validPhone($str)
    {
        return strlen($str) === 11 && $str[0] == '7';
    }
}

if (!function_exists('pre')) {
    function pre($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}

if (!function_exists('strMakeRand')) {
    function strMakeRand($minlength, $maxlength, $useupper, $usespecial, $usenumbers)
    {
        $key = '';
        $charset = "abcdefghkmnpqrstuvxyz";
        if ($useupper) $charset .= "ABCDEFGHKMNPRSTUVXYZ";
        if ($usenumbers) $charset .= "23456789";
        if ($usespecial) $charset .= "~@#$%^*()_+-={}|][";
        if ($minlength > $maxlength) $length = mt_rand($maxlength, $minlength);
        else $length = mt_rand($minlength, $maxlength);
        for ($i = 0; $i < $length; $i++) $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
        return $key;
    }
}

if (!function_exists('intMakeRand')) {
    function intMakeRand($minlength, $maxlength)
    {
        $key = '';
        $charset = "23456789";
        if ($minlength > $maxlength) $length = mt_rand($maxlength, $minlength);
        else $length = mt_rand($minlength, $maxlength);
        for ($i = 0; $i < $length; $i++) $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
        return $key;
    }
}

if (!function_exists('assetVersioned')) {
    function assetVersioned($path)
    {
        return asset($path) . '?' . @filemtime(public_path($path)) ?: 0;
    }
}