<?php

namespace ChingShop\Actions;

use ChingShop\User\User;
use ChingShop\User\Role;

use ChingShop\Validation\Validation;
use ChingShop\Validation\ValidationFailure;
use Illuminate\Contracts\Hashing\Hasher;

class MakeUser
{
    /** @var Validation */
    private $validation;

    /** @var Hasher */
    private $hasher;

    /** @var Role */
    private $roleResource;

    /** @var array */
    private $validationRules = [
        'email'    => 'required|email',
        'password' => 'required|min:8|max:127'
    ];

    /** @var array of strings */
    private $messages = [];

    /**
     * @param Validation $validation
     * @param Hasher $hasher
     * @param Role $roleResource
     */
    public function __construct(Validation $validation, Hasher $hasher, Role $roleResource)
    {
        $this->validation = $validation;
        $this->hasher = $hasher;
        $this->roleResource = $roleResource;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $isStaff
     * @return User
     * @throws ValidationFailure
     */
    public function make(string $email, string $password, bool $isStaff): User
    {
        if (!$this->validate($email, $password)) {
            throw new ValidationFailure();
        }

        $user = new User;
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
     * @return \Illuminate\Validation\Validator
     */
    private function validate(string $email, string $password): bool
    {
        return $this->validation->passes(
            compact('email', 'password'),
            $this->validationRules
        );
    }
}
