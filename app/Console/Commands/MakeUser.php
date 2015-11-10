<?php

namespace ChingShop\Console\Commands;

use ChingShop\User\Role;
use ChingShop\User\User;

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

        $user = new User;
        $user->setAttribute('email', $email);
        $user->setAttribute('password', bcrypt($password));
        $user->save();

        if ($this->option('staff')) {
            $staffRole = (new Role)->mustFindByName(Role::STAFF);
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
        return strtolower(str_random(16)) . '@ching-shop.com';
    }
}
