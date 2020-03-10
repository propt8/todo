<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Transform the resource into an array.
     *
     * @param User $user
     * @return array
     *
     * @OA\Schema(
     *     schema="User",
     *          @OA\Property(
     *              property="id",
     *              description="User ID",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="email",
     *              description="User Email",
     *              type="string",
     *          ),
     *          @OA\Property(
     *              property="admin status",
     *              description="User admin status",
     *              type="boolean",
     *          )
     * )
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'admin' => (bool)$user->admin
        ];
    }
}
