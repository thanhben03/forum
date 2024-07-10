<?php

namespace App\Api\Services\User;

use  App\Api\Repositories\User\UserRepositoryInterface;

use Illuminate\Http\Request;

class UserService implements UserServiceInterface
{
    /**
     * Current Object instance
     *
     * @var array
     */
    protected $data;

    protected $repository;


    public function __construct(
        UserRepositoryInterface  $repository,
    )
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {

        $data = $request->validated();
        $user = $this->repository->create($data);

        return $user;
    }

    public function update(Request $request): object|bool
    {

        $data = $request->validated();
        $data['latitude'] = $data['lat'];
        $data['longitude'] = $data['lng'];

        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        if (!array_key_exists('notification_preference', $data)) {
            $data['notification_preference'] = AutoNotification::Off;
        }

        return $this->repository->update($data['id'], $data);

    }

    public function delete($id): object|bool
    {
        return $this->repository->delete($id);

    }

}
