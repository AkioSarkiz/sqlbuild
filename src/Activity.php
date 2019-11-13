<?php

declare(strict_types = 1);


namespace SQLBuild;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Class Activity - класс для composer
 * @package SQLBuild
 */
class Activity implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io) {}
}
