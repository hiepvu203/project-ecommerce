<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StaffRepository;
use App\Models\User;
use App\Notifications\StaffAccountCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StaffService
{
    public function __construct(
        protected StaffRepository $staffRepository
    ) {}

    public function createStaff(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->staffRepository->createUser([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'type'              => User::TYPE_ADMIN,
                'email_verified_at' => Carbon::now(),
            ]);

            $role = $this->staffRepository->findRoleByName($data['role']);
            if (!$role) {
                throw new \Exception('Role does not exist.', 422);
            }

            $this->staffRepository->assignRoleToUser($user->id, $role->id);

            $user->notify(new StaffAccountCreated($data['password']));

            return $user;
        });
    }

    public function getSystemStaff(int $perPage = 10)
    {
        return $this->staffRepository->getSystemStaff($perPage);
    }
}
