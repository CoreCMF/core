<?php

namespace CoreCMF\Core\Support\Package;

/**
 * [Psr4 Psr4命名空间解析]
 */
class Psr4
{
    public function namespaceDir($namespace)
    {
        $psr4 = require base_path().'/vendor/composer/autoload_psr4.php';
        $namespace = str_replace('\\\\',"\\",$namespace.'\\');//防止忘记加／
        return array_key_exists($namespace,$psr4)? $psr4[$namespace][0]: false;
    }
}
