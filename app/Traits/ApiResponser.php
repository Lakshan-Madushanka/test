<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private string $successMsg = 'Query succeeded !';
    private string $errorMsg = 'Error occurred !';


    public function showOne(
        mixed $data,
        array|string|null $message = '',
        int $statusCode = 200
    ): JsonResponse {
        return $this->singleResponse($message, $data, $statusCode);
    }

    public function showMany(
        array|Collection|null $data,
        array|string|null $message = '',
        int $statusCode = 200
    ): JsonResponse {
        if ($message == '') {
            $message = $this->successMsg;
        }

        if ($data instanceof EloquentCollection && ! $data->isEmpty()) {
            $data = $this->transformer($data);
        }

        return response()->json(['massage' => $message, 'data' => $data], $statusCode);
    }

    public function showMessage(
        mixed $data = null,
        array|string|null $message = '',
        int $statusCode = 200
    ): JsonResponse {
        if ($message == '') {
            $message = $this->successMsg;
        }

        return $this->singleResponse($message, $data, $statusCode);
    }

    public function showError(array|string|null $message, array|null $data = null, int $statusCode = 422): JsonResponse
    {
        if ($message == '') {
            $message = $this->errorMsg;
        }

        return $this->singleResponse($message, $data, $statusCode);
    }

    private function singleResponse($message, $data, $statusCode)
    {
        $response = ['message' => $message, 'data' => $data];

        if (is_null($response['data'])) {
            unset($response['data']);
        }

        return response()->json($response, $statusCode);
    }

    private function transformer(Collection|Model $data): JsonResource|Collection
    {
        $model = $data;

        if ($model instanceof Collection) {
            $model = $model[0];
        }

        return $data;
    }
}
