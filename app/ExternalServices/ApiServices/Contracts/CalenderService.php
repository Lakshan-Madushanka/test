<?php

namespace App\ExternalServices\ApiServices\Contracts;

use Illuminate\Support\Collection;
use stdClass;

interface CalenderService
{
    public function getHolidays(array $filters): Collection;

    public function validateYearFilter(int $requestedYear): ?int;

    public function formatHolidays(stdClass $data, $filters): Collection;

    public function applyFilters(Collection $data, array $filters): Collection;

    public function sort(Collection $data): Collection;

    public function group(Collection $data, string $type): Collection;

    public function prepareErrorObject(Collection $errorInfo): Collection;
}
