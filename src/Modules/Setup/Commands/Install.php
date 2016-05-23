<?php


namespace MezzoLabs\Mezzo\Modules\Installer\Setup;


use CategoryTableSeeder;
use CountriesSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use MezzoLabs\Mezzo\Console\Commands\MezzoCommand;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Modules\User\Commands\SeedPermissions;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\UserRepository;

class Install extends MezzoCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mezzo:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare Mezzo for further usage.';
    /**
     * @var SeedPermissions
     */
    private $seedPermissions;
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var Seeder
     */
    private $databaseSeeder;

    /**
     * Create a new command instance.
     *
     * @return \MezzoLabs\Mezzo\Modules\Generator\Commands\GenerateForeignFields
     */
    public function __construct(SeedPermissions $seedPermissions, UserRepository $users, Seeder $databaseSeeder)
    {
        parent::__construct();
        $this->seedPermissions = $seedPermissions;
        $this->users = $users;
        $this->databaseSeeder = $databaseSeeder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Model::unguard();
        PermissionGuard::setActive(false);

        $this->comment('
MM    MM
MMM  MMM   eee  zzzzz zzzzz  oooo
MM MM MM ee   e   zz    zz  oo  oo
MM    MM eeeee   zz    zz   oo  oo
MM    MM  eeeee zzzzz zzzzz  oooo

      - Handcrafted in Sasbachwalden

');

        $this->info('== Mezzo CMF Installer ==');

        $this->migrate();

        $user = $this->createSuperUser();

        $email = ($user) ? $user->email : $this->ask('Enter an existing email to seed permissions:');

        $this->seedPermissions($email);

        $this->seedTables();

        $this->info('');
        $this->info('Done.');
        $this->info('');

        $this->comment('Your tasks:');
        $this->comment('- Add the zipcodes.sql');
        $this->comment('- Run "bower update".');
        $this->comment('- Go to /mezzo and login to your admin account. (Psst... the address is ' . $email . ' )');
        $this->comment('- Enjoy yourself :*');

    }


    /**
     * @return \App\User
     * @throws \MezzoLabs\Mezzo\Exceptions\RepositoryException
     */
    protected function createSuperUser()
    {
        $this->info('1.) Create a superuser');

        $email = $this->ask('Enter email');

        if (!$email) {
            $this->warn('Will not create a super user');
            return null;
        }

        $password = $this->secret('Enter password');

        $firstName = $this->ask('First Name', 'Super');
        $lastName = $this->ask('Last Name', 'Administrator');

        return $this->users->create([
            'email' => $email,
            'password' => bcrypt($password),
            'gender' => 'm',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'confirmed' => true,
            'backend' => true
        ]);
    }

    /**
     * @return int
     */
    protected function seedPermissions(string $email)
    {
        $this->info('2.) Seed permissions');

        return Artisan::call('mezzo:permissions:seed', ['email' => $email]);

    }

    protected function seedTables()
    {
        $this->info('3.) Seed table rows');


        $this->info('3.1.) Seed countries');
        $this->databaseSeeder->call(CountriesSeeder::class);

        $this->info('3.2.) Seed categories');
        $this->databaseSeeder->call(CategoryTableSeeder::class);
    }

    protected function migrate()
    {
        $this->info('0.) Migrate tables');

        return \Artisan::call('migrate');
    }

}