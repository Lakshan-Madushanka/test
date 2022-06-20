<?php

namespace App\ExternalServices\ApiServices\CalenderService;

use App\ExternalServices\ApiServices\Contracts\CalenderService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use stdClass;

class GoogleCalenderService implements CalenderService
{
    private string $holidaysUrl = 'https://www.googleapis.com/calendar/v3/calendars/{REGION}%23holiday%40group.v.calendar.google.com/events?key={APIKEY}';

    /**
     * @param  array|string|string[]  $url
     */
    public function __construct()
    {
        $this->setUrl($this->holidaysUrl);
    }

    public function setUrl(array|string $url): void
    {
        $url = str_replace('{REGION}', env('REGION'), $this->holidaysUrl);
        $url = str_replace('{APIKEY}', env('GOOGLE_API_KEY'), $url);

        $this->holidaysUrl = $url;
    }

    public function getHolidays(array $filters): Collection
    {
        $response = Http::get($this->holidaysUrl);

        if (! $response->ok()) {
            return $this->prepareErrorObject($response->collect());
        }

        return $this->formatHolidays(json_decode($response), $filters);
    }

    public function formatHolidays(stdClass $data, $filters): Collection
    {
        $requestedYear = isset($filters['year']) ? $this->validateYearFilter((int) $filters['year']) : null;
        $holidays = collect();

        $items = $data->items;

        foreach ($items as $item) {
            $tempArray = [];
            $year = Carbon::parse($item->start->date)->year;

            if ($requestedYear && $year !== $requestedYear) {
                continue;
            }

            $tempArray['date'] = $item->start->date;
            $tempArray['name'] = $item->summary;

            $holidays->push([$tempArray]);
        }

        $holidays = $holidays->collapse();

        $holidays = $this->applyFilters($holidays, $filters);

        return $holidays;
    }

    public function validateYearFilter(int $requestedYear): ?int
    {
        $currentYear = now()->year;

        return in_array($requestedYear, [
            $currentYear - 1,
            $currentYear + 1,
            $currentYear,
        ]) ? $requestedYear : null;
    }

    public function applyFilters(Collection $data, array $filters): Collection
    {
        if (isset($filters['sort'])) {
            $data = $this->sort($data);
        }

        if (isset($filters['groupBy']) && $filters['groupBy'] === 'month') {
            $data = $this->group($data, 'month');
        }

        return $data;
    }

    public function sort(Collection $data): Collection
    {
        return $data->sortBy(function ($item) {
            return Carbon::parse($item['date'])->timestamp;
        })->values();
    }

    public function group(Collection $data, string $type): Collection
    {
        return match ($type) {
            'month' => $data->groupBy(
                function ($item) {
                    return Carbon::parse($item['date'])->monthName;
                }
            ),
        };
    }

    public function prepareErrorObject(Collection $errorInfo): Collection
    {
        $errorObject = collect();

        $errorObject['message'] = $errorInfo['error']['message'];
        $errorObject['errors'] = $errorInfo['error']['errors'];
        $errorObject['code'] = $errorInfo['error']['code'];

        return $errorObject;
    }
}
