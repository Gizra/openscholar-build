<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit50d907cb4bcd11132d65a88eb49e9722
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
            'Symfony\\Component\\EventDispatcher\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
    );

    public static $prefixesPsr0 = array (
        'G' => 
        array (
            'GitWrapper' => 
            array (
                0 => __DIR__ . '/..' . '/cpliakas/git-wrapper/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit50d907cb4bcd11132d65a88eb49e9722::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit50d907cb4bcd11132d65a88eb49e9722::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit50d907cb4bcd11132d65a88eb49e9722::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
