<?php


namespace Quetzal\Core;

use DI\DependencyInjection\Container;
use Quetzal\Core\Database\Database;

class App
{
    private $reg;
    private $core_services;

    private function __construct()
    {
        $this->reg = Register::instance();
        $this->core_services = new Container();
//        $this->registerCoreServices();
    }

    public static function run() : void
    {
        $app = new App();
        $appHelper = $app->getAppHelper();
        $appHelper->setup();
        $app->registerCoreServices();
        $app->handleRequest();
    }

    /**
     * TODO: Consider DI instead of Registry
     */
    public function registerCoreServices() {
        $this->reg->setDatabase(new Database());
//        $this->core_services->set(SettingsManager::init());
    }

    private function getAppHelper(): ?AppHelper
    {
        return $this->reg->getAppHelper();
    }

    private function handleRequest()
    {
        $request = $this->reg->getRequest();
        $appController = new AppController();
        $ctrl = $appController->getController($request);
        $ctrl['controller']->execute($request, $ctrl['action']);
    }
}
