<?php

namespace App\Domains\Calender\Controllers;

use App\ExternalServices\ApiServices\Contracts\CalenderService;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CalenderController extends Controller
{
    use ApiResponser;

    public function retrieveRegionHolidays(CalenderService $calenderService, Request $request)
    {
        $data = $calenderService->getHolidays($request->query());

        if ($data->has('errors')) {
            return $this->showError($data['message'], $data['errors'], $data['code']);
        }

        return $this->showMany($data);
    }
}
