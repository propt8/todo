<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ErrorTransformer extends TransformerAbstract
{
    /**
     * @param string $errorCode
     * @param array|null $errors
     * @return array
     *
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     @OA\Property(
     *         property="errorCode",
     *         type="string"
     *     ),
     *     @OA\Property(
     *         property="errors",
     *         description="Error list",
     *         type="object",
     *     ),
     * )
     */
    public function transform(string $errorCode, ?array $errors = [])
    {
        return [
            'errorCode' => $errorCode,
            'errors' => $errors,
        ];
    }
}
