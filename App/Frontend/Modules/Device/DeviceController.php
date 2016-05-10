<?php
namespace App\Frontend\Modules\Device;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Mobile_Detect;

class DeviceController extends BackController
{

    public function executeIndex(HTTPRequest $Request)
    {

        $Detect = new Mobile_Detect;
        $deviceType = "<b>" . ($Detect->isMobile() ? ($Detect->isTablet() ? 'une tablette' : 'un téléphone') : 'un ordinateur') . " </b><br>";

        foreach ($Detect->getProperties() as $name => $match) {

            $check = $Detect->version($name);
            if ($check !== false) {

                $deviceType .= " Le navigateur est composé de <i>" . $name . "</i>";
                $deviceType .= " avec la version : " . $check . " <br>";
            }

        }

        $this->page->addVar('device', $deviceType);
    }
}