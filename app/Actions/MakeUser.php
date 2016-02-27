<?php

namespace ChingShop\Actions;

use ChingShop\User\Role;
use ChingShop\User\User;
use ChingShop\Validation\ValidationFailure;
use ChingShop\Validation\ValidationInterface;
use Illuminate\Contracts\Hashing\Hasher;

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
    public function __construct(ValidationInterface $validation, Hasher $hasher, Role $roleResource)
    {
        $this->validation = $validation;
        $this->hasher = $hasher;
        $this->roleResource = $roleResource;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool   $isStaff
     *
     * @throws ValidationFailure
     *
     * @return User
     */
    public function make(string $email, string $password, bool $isStaff): User
    {
        if (!$this->validate($email, $password)) {
            throw new ValidationFailure();
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
            compact('email', 'password'),
            $this->validationRules
        );
    }
}
