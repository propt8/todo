<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class StatusResponseTransformer extends TransformerAbstract
{
    /**
     * @param bool $status
     * @return array
     *
     * @OA\Schema(
     *     schema="Status",
     *          @OA\Property(
     *              property="status",
     *              description="Status of Request",
     *              type="boolean",
     *          ),
     * )
     */
    public function transform(bool $status)
    {
        return [
            'status' => $status,
        ];
    }
}
