<?php

$bootstrap = require_once __DIR__."/vendor/autoload.php";

$config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__.'/config.yml'));

$bootstrap = new \Checker\Bootstrap($config);

$watcher = $bootstrap->getWatcher();

$watcher->run();
