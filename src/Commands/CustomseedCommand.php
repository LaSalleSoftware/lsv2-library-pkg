<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

/**
 * Unfortunately, running "php artisan db:seed" at the command line does not run this package's seeders.
 * There may be other ways to do it, but the best way to run a package's seeders is to use the
 * "--class=" parmaeter in the db:seed command. Very unfortunately, when using this parameter
 * in a Laravel app -- not the command line! -- this parameter is not rendered as a parameter.
 *
 * So, I put together this little custom artisan command that runs db:seed with the --class parameter.
 *
 * Now, it is easy to run this package's database seeders from Laravel. A critical thing needed for testing!
 *
 * @package Lasallesoftware\Library\Commands\CustomseedCommand
 */

namespace Lasallesoftware\Library\Commands;

// Laravel classes
use Illuminate\Console\Command;

class CustomseedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'lslibrary:customseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the custom database seeder.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //consoleOutput()->comment('Starting the custom database seeder...');
        $this->info('Starting the custom database seeder...');

        $this->call('db:seed', [
            '--class' => 'Lasallesoftware\\Library\\Database\\DatabaseSeeds\\DatabaseSeeder',
        ]);
    }
}
