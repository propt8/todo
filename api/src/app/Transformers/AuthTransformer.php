<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
{
    /**
     * Transform the resource into an array.
     *
     * @param array $data
     * @return array
     *
     * @OA\Schema(
     *     schema="Auth",
     *          @OA\Property(
     *              property="accessToken",
     *              description="Access Token",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="tokenType",
     *              description="Token Type",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="expiresIn",
     *              description="Token Expires in",
     *              type="integer",
     *          )
     * )
     */
    public function transform(array $data)
    {
        return [
            'accessToken' => $data['access_token'],
            'tokenType' => $data['token_type'],
            'expiresIn' => $data['expires_in']
        ];
    }
}
