<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitefe6e86c9a057387e651dc32bcce69b2
{
    public static $files = array (
        '0d59ee240a4cd96ddbb4ff164fccea4d' => __DIR__ . '/..' . '/symfony/polyfill-php73/bootstrap.php',
        '5d9c5be1aa1fbc12016e2c5bd16bbc70' => __DIR__ . '/..' . '/dusank/knapsack/src/collection_functions.php',
        'e5fde315a98ded36f9b25eb160f6c9fc' => __DIR__ . '/..' . '/dusank/knapsack/src/utility_functions.php',
        'eb560c198217526cfde8ceb63ae508de' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p8.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php73\\' => 23,
            'Symfony\\Contracts\\HttpClient\\' => 29,
            'Symfony\\Component\\HttpClient\\' => 29,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'D' => 
        array (
            'DusanKasan\\Knapsack\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php73\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php73',
        ),
        'Symfony\\Contracts\\HttpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/http-client-contracts',
        ),
        'Symfony\\Component\\HttpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/http-client',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'DusanKasan\\Knapsack\\' => 
        array (
            0 => __DIR__ . '/..' . '/dusank/knapsack/src',
        ),
    );

    public static $classMap = array (
        'JsonException' => __DIR__ . '/..' . '/symfony/polyfill-php73/Resources/stubs/JsonException.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitefe6e86c9a057387e651dc32bcce69b2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitefe6e86c9a057387e651dc32bcce69b2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitefe6e86c9a057387e651dc32bcce69b2::$classMap;

        }, null, ClassLoader::class);
    }
}
