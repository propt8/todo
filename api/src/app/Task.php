<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'description'
    ];

    private int $id;
    private int $user_id;
    private string $title;
    private string $description;
    private \DateTime $created_at;
    private \DateTime $updated_at;

    /**
     * @param $query
     * @param int $userId
     * @param bool $admin
     * @return mixed
     */
    public function scopeSpecial($query, int $userId, bool $admin = false)
    {
        if(!$admin) {
            return $query->where('user_id', $userId);
        }

        return $query;
    }
}
