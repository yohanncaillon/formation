<?php
namespace App\Frontend\Modules\Device;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Mobile_Detect;

class DeviceController extends BackController
{

    public function executeIndex(HTTPRequest $request)
    {

        $detect = new Mobile_Detect;
        $deviceType = "<b>" . ($detect->isMobile() ? ($detect->isTablet() ? 'une tablette' : 'un téléphone') : 'un ordinateur') . " </b><br>";

        foreach ($detect->getProperties() as $name => $match) {

            $check = $detect->version($name);
            if ($check !== false) {

                $deviceType .= " Le navigateur est composé de <i>" . $name . "</i>";
                $deviceType .= " avec la version : " . $check . " <br>";
            }

        }

        $this->page->addVar('device', $deviceType);
    }

}