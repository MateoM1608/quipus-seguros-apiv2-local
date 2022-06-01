<?php

namespace App\Http\Controllers\Cloud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\DigitalOceanServices;

class AdministratorController extends Controller
{
    use DigitalOceanServices;

    public function newEnvironment(Request $request)
    {
        $instance = $this->getDataBases();
        $database = $this->createDataBase($request->identification, $instance['uuid']);

        sleep(30);
        $this->createUserDB($request->identification, $instance, $database);

    }

    public function getDataBases() {
        $instance = $this->DB();
        if (!$instance) {
            $instance = $this->createCluster();
            sleep(210);
        }
        return $instance;
    }
}
