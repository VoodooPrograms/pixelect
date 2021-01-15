<?php


namespace Quetzal\Core;

use Quetzal\Core\Http\Request;
use Quetzal\Core\Routing\UrlResolver;
use Quetzal\Core\Security\Guard;

class AppController
{
    private $setmgr = null;
    private $reg;

    public function __construct()
    {
        $this->reg = Register::instance();
        $this->reg->setAppcontroller($this);
        $this->setmgr = $this->reg->getSettingsManager();
        $this->reg->setGuard(new Guard());
    }

    public function getController(Request $request): array
    {
        if (isset($_SERVER["REQUEST_METHOD"])) {
            $resolver = new UrlResolver();
        } else {
            //$resolver = new UrlResolver();
            // $request = new CliRequest();
            // There will be more type of request eg. CliRequest
        }
        $routing = $this->reg->getSettingsManager()['routing']['routing'];
        $controler = $this->reg->getResolver()->match($request, $routing);
        return $controler;
    }

    /*
     * This will be function responsible for getting proper view
     */
    public function getView(Request $request)
    {
    }
}
