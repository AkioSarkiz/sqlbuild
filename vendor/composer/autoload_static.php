<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita9e10875dea1e6fce4b119b94e23a1c8
{
    public static $classMap = array (
        'SQLBuild\\AbstractCollection' => __DIR__ . '/../..' . '/src/collections/AbstractCollection.php',
        'SQLBuild\\Activity' => __DIR__ . '/../..' . '/src/Activity.php',
        'SQLBuild\\ColumnCollection' => __DIR__ . '/../..' . '/src/collections/ColumnCollection.php',
        'SQLBuild\\SQLBuild' => __DIR__ . '/../..' . '/src/SQLBuild.php',
        'SQLBuild\\SQLOperator' => __DIR__ . '/../..' . '/src/SQLOperator.php',
        'SQLBuild\\SQLType' => __DIR__ . '/../..' . '/src/SQLType.php',
        'SQLBuild\\SelectCollection' => __DIR__ . '/../..' . '/src/collections/SelectCollection.php',
        'SQLBuild\\Set' => __DIR__ . '/../..' . '/src/classes/Set.php',
        'SQLBuild\\SetCollection' => __DIR__ . '/../..' . '/src/collections/SetCollection.php',
        'SQLBuild\\SortCollection' => __DIR__ . '/../..' . '/src/collections/SortCollection.php',
        'SQLBuild\\TableCollection' => __DIR__ . '/../..' . '/src/collections/TableCollection.php',
        'SQLBuild\\Value' => __DIR__ . '/../..' . '/src/classes/Value.php',
        'SQLBuild\\ValueCollection' => __DIR__ . '/../..' . '/src/collections/ValueCollection.php',
        'SQLBuild\\Where' => __DIR__ . '/../..' . '/src/classes/Where.php',
        'SQLBuild\\WhereCollection' => __DIR__ . '/../..' . '/src/collections/WhereCollection.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInita9e10875dea1e6fce4b119b94e23a1c8::$classMap;

        }, null, ClassLoader::class);
    }
}