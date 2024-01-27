<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit41f0d87ca16800f448aaf1e29adfd07d
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit41f0d87ca16800f448aaf1e29adfd07d', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit41f0d87ca16800f448aaf1e29adfd07d', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit41f0d87ca16800f448aaf1e29adfd07d::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
