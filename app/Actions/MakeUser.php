<?php

namespace ChingShop\Actions;

use ChingShop\User\User;
use ChingShop\User\UserResource;

use ChingShop\Validation\Validation;
use ChingShop\Validation\ValidationFailure;
use Illuminate\Contracts\Hashing\Hasher;

class MakeUser
{
        /** @var Validation */
    private $validation;

    /** @var Hasher */
    private $hasher;

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
     */
    public function __construct(Validation $validation, Hasher $hasher)
    {
        $this->validation = $validation;
        $this->hasher = $hasher;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $isStaff
     * @return User
     */
    public function make(string $email, string $password, bool $isStaff): User
    {
        if (!$this->validate($email, $password)) {
            throw new ValidationFailure();
        }

        $userResource = new UserResource;
        $userResource->email = $email;
        $userResource->password = $this->hasher->make($password);

        return new User($userResource);
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
