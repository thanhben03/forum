<?php

namespace App\Api\Repositories\User;
use App\Repositories\EloquentRepositoryInterface;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function count();
    public function searchAllLimit($value = '', $meta = [], $limit = 10);
    public function chartUser(array $dateBetween);
    public function createUser($discount,$DataUser);
}
