<?php

namespace ChingShop\Console\Commands;

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
    protected $signature = 'make:user {--email=} {--admin}';

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
        $email = $this->option('email') ?
            $this->option('email') : $this->_generateEmailAddress();

        $password = $this->secret('Give a password (type "r" for random password)');
        if ($password === 'r') {
            $password = str_random(18);
            $this->warn("Setting randomly generated password `{$password}`");
        }

        $newUser = new UserResource;
        $newUser->setAttribute('email', $email);
        $newUser->setAttribute('password', bcrypt($password));
        $newUser->save();

        $this->info(
            "Created new user with ID `{$newUser->id}` and email `{$newUser->email}`"
        );
    }

    /**
     * @return string
     */
    private function _generateEmailAddress()
    {
        return strtolower(str_random(16)) . '@ching-shop.com';
    }
}
