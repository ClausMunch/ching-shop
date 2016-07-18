<?php

namespace ChingShop\Console\Commands;

use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use Illuminate\Console\Command;

/**
 * Class MakeUser.
 */
class MakeUser extends Command
{
    const PASSWORD_MINIMUM = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {--email=} {--password=} {--staff}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new user.';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('email')) {
            $email = $this->option('email');
        } else {
            $email = $this->generateEmailAddress();
        }

        if ($this->option('password')) {
            $password = $this->option('password');
        } else {
            $password = str_random(18);
            $this->warn("Setting randomly generated password `{$password}`");
        }

        $user = $this->userResource();
        $user->setAttribute('email', $email);
        $user->setAttribute('password', bcrypt($password));
        $user->save();

        if ($this->option('staff')) {
            $staffRole = $this->roleResource()->mustFindByName(Role::STAFF);
            $user->roles()->sync([$staffRole->id]);
            $this->warn('Granted staff role to new user');
        }

        $this->info(
            "Created new user with ID `{$user->id}` and email `{$user->email}`"
        );
    }

    /**
     * @return string
     */
    private function generateEmailAddress()
    {
        return strtolower(str_random(16)).'@ching-shop.com';
    }

    /**
     * @return User
     */
    private function userResource(): User
    {
        return app(User::class);
    }

    /**
     * @return Role
     */
    private function roleResource(): Role
    {
        return app(Role::class);
    }
}
