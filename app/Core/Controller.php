<?php


namespace Quetzal\Core;

/*
 * Base class for all user controllers.
 * It will be responsible for executing action in controller and catch status of this action
 * For now we using here template method with abstract method index.
 *  In later versions there won't be anything to override in user controllers
 */

use Quetzal\Core\Http\Request;

abstract class Controller
{
    protected $registry;

    final public function __construct()
    {
        $this->registry = Register::instance();
    }

    public function execute(Request $request, string $action)
    {
        $status = $this->$action($request);
        $request->setStatus($status);
    }

    // Rendering template
    protected function render(string $template=null, array $parr=[])
    {
        $template_path = $this->registry->getSettingsManager()['config']['config']['template_path'] . $template;
        if(file_exists($template_path))
        {
            //if any variable named the same as the key exist <=> extract return number != sizeof($parr), then fail
            if (extract($parr, EXTR_SKIP) != sizeof($parr))
                return 1; //fail
            $vs = new ViewSupport();  //important $vs after extract
            ob_start();
            include $template_path;
            $output = ob_get_clean();
            print $output;
            return 0; //success
        } else
            return 1; //fail
    }

}
