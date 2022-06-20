<?php

namespace Tests\Feature\Calender;

use Tests\TestCase;

class CalenderFunctionalityTest extends TestCase
{
    public function test_holidays_can_obtain()
    {
        $response = $this->getJson(route('calender.retrieveRegionHolidays'));

        $response->assertStatus(200);
    }

    public function test_holidays_can_obtain_using_group_by_filter()
    {
        $params = '?' + http_build_query(['groupBy' => 'month']);

        $response = $this->getJson(route('calender.retrieveRegionHolidays'.$params));

        $response->assertStatus(200);
    }

    public function test_holidays_can_obtain_by_sorted()
    {
        $params = '?' + http_build_query(['sort' => 'true']);

        $response = $this->getJson(route('calender.retrieveRegionHolidays'.$params));

        $response->assertStatus(200);
    }

    public function test_holidays_can_obtain_by_year()
    {
        $params = '?' + http_build_query(['year' => now()->year]);

        $response = $this->getJson(route('calender.retrieveRegionHolidays'.$params));

        $response->assertStatus(200);
    }
}
