<?php
$applicationRoot = __DIR__ . '/../../../../';

chdir($applicationRoot);

$loader = require_once('vendor/autoload.php');
$loader->add('Sds\\IdentityModule\\Test', __DIR__);
$loader->add('Sds\\DoctrineExtensionsModule\\Test', __DIR__ . '/../../doctrine-extensions-module/tests');
$loader->add('Sds\\ModuleUnitTester', __DIR__ . '/../../../superdweebie/module-unit-tester/lib');

$config = include(__DIR__ . '/test.application.config.php');

\Sds\ModuleUnitTester\AbstractTest::setApplicationConfig($config);