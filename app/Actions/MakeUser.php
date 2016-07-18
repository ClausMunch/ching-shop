<?php

namespace ChingShop\Actions;

use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use ChingShop\Validation\ValidationInterface;
use DomainException;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class MakeUser.
 */
class MakeUser
{
    /** @var ValidationInterface */
    private $validation;

    /** @var Hasher */
    private $hasher;

    /** @var Role */
    private $roleResource;

    /** @var array */
    private $validationRules = [
        'email'    => 'required|email',
        'password' => 'required|min:8|max:127',
    ];

    /**
     * @param ValidationInterface $validation
     * @param Hasher              $hasher
     * @param Role                $roleResource
     */
    public function __construct(
        ValidationInterface $validation,
        Hasher $hasher,
        Role $roleResource
    ) {
        $this->validation = $validation;
        $this->hasher = $hasher;
        $this->roleResource = $roleResource;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool   $isStaff
     *
     * @throws \DomainException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return User
     */
    public function make(string $email, string $password, bool $isStaff): User
    {
        if (!$this->validate($email, $password)) {
            throw new DomainException();
        }

        $user = new User();
        $user->email = $email;
        $user->password = $this->hasher->make($password);

        if ($isStaff) {
            $staffRole = $this->roleResource->mustFindByName(Role::STAFF);
            $staffRole->users()->save($user);
        }

        return $user;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return bool
     */
    private function validate(string $email, string $password): bool
    {
        return $this->validation->passes(
            [
                'email'    => $email,
                'password' => $password,
            ],
            $this->validationRules
        );
    }
}
