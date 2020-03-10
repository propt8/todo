<?php

namespace App\Entities;

use App\Task;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class User extends Entity
{
    /**
     * @var int
     */
    protected integer $id;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $name;

    /**
     * EntityInterface constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
    }
}
