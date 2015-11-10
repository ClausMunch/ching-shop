<?php

namespace ChingShop\Console\Commands;

use ChingShop\User\Role;
use ChingShop\User\RoleResource;
use ChingShop\User\UserResource;

use Illuminate\Console\Command;

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
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
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

        $userResource = new UserResource;
        $userResource->setAttribute('email', $email);
        $userResource->setAttribute('password', bcrypt($password));
        $userResource->save();

        if ($this->option('staff')) {
            $staffRole = (new RoleResource)->mustFindByName(Role::STAFF);
            $userResource->roles()->sync([$staffRole->id]);
            $this->warn('Granted staff role to new user');
        }

        $this->info(
            "Created new user with ID `{$userResource->id}` and email `{$userResource->email}`"
        );
    }

    /**
     * @return string
     */
    private function generateEmailAddress()
    {
        return strtolower(str_random(16)) . '@ching-shop.com';
    }
}
