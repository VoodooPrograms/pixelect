<?php

declare(strict_types=1);

require_once("vendor/autoload.php");

//$container = new \DI\DependencyInjection\Container();
//$container->set(new stdClass());
//var_dump($container);


use Quetzal\Core\App;
use SM\SettingsManager\SettingsManager;

$ROOT_DIR = dirname(__FILE__);
SettingsManager::setSettingsDirPath($ROOT_DIR . '/Config/');
App::run();

//$smanager = SettingsManager::loadFromInitFile($ROOT_DIR . '/Config/settings.yaml');
//echo '<pre>';
//var_dump($smanager->getSettings());
//echo '</pre>';
