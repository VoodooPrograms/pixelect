<?php


namespace Quetzal\Core;

use Quetzal\Core\Http\HttpRequest;
use SM\SettingsManager\SettingsManager;

class AppHelper
{
    private $mainSettingsFile = "app/Config/settings.yaml";
    private $registry;
    private $settings_manager;

    public function __construct()
    {
        $this->registry = Register::instance();
        $this->settings_manager = SettingsManager::loadFromInitFile($this->mainSettingsFile);;
        $this->registry->setSettingsManager($this->settings_manager);
    }

    public function setup()
    {
        if (isset($_SERVER["REQUEST_METHOD"])) {
            $request = new HttpRequest();
        } else {
            throw new AppException('This Request is not proper HTTP Request');
        }
        $this->registry->setRequest($request);
    }
}
