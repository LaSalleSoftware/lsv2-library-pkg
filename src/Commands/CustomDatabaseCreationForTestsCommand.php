<?php

/**
 * This file is part of the Lasalle Software library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-library-pkg
 *
 */

namespace Lasallesoftware\Library\Commands;

// LaSalle Software class
use Lasallesoftware\Library\Common\Commands\CommonCommand;

// Laravel classes
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class CustomDatabaseCreationForTestsCommand
 *
 * 
 * Drops the current database, creates a new database, creates the tables, and seeds the tables.
 * Stictly for software tests, specifically Dusk tests. 
 * This artisan command is expected to be called from Dusk tests, not from the command line. 
 * 
 * 
 * *** THIS ARTISAN COMMAND DROPS YOUR DATABASE WITH NO PROMPT!! ***
 *
 * 
 * Inspired by https://github.com/mnabialek/laravel-quick-migrations
 * 
 * Adapted from
 * https://github.com/laravel/framework/blob/7.x/src/Illuminate/Database/Console/Migrations/FreshCommand.php
 *
 * @package Lasallesoftware\Library\Commands\CustomDatabaseCreationForTestsCommand
 */
class CustomDatabaseCreationForTestsCommand extends CommonCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:customdatabasecreationfortests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command to create a seeded database for software testing.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $environment = trim(strtolower(app('config')->get('app.env')));

        if ($environment == "production") {
            $this->info('Cancelled running lslibrary:customdrop because this is a production environment.');
            return;
        }

        if ($environment != "testing") {
            $this->info('Cancelled running lslibrary:customdrop because this is *NOT* a test environment.');
            return;
        }

        $database = $this->input->getOption('database');

        $this->call('db:wipe', array_filter([
            '--database' => $database,
            '--force'    => true,
        ]));

        DB::unprepared(File::get(base_path().'/tests/lasallesoftware.sql'));        
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
        ];
    }
}