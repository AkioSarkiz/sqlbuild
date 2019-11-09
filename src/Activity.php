<?php

declare(strict_types = 1);


namespace SQLBuild;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Activity implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io) {}
}
