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
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
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
use http\Exception\BadQueryStringException;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

/**
 * Class LasalleinstallpartoneCommand
 *
 * First of two artisan command installation scripts
 *
 * @package Lasallesoftware\Library\Commands\LasalleinstallpartoneCommand
 */
class LasalleinstalladminappCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:lasalleinstalladminapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LaSalle Software administrative app installation.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The base directory.
     *
     * @var string
     */
    protected $baseDir;

    /**
     * Create a new config command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files   = $files;
        $this->baseDir = __DIR__.'/tmp';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        echo "\n\n";
        $this->info('================================================================================');
        $this->info('                  lslibrary:lasalleinstalladminapp ');
        $this->info('================================================================================');
        echo "\n\n";

        if (env('LASALLE_APP_NAME') != 'adminbackendapp') {
            $this->line("This installation artisan command is specifically for my LaSalle Software's admin application.");
            $this->line('You are installing my ' . mb_strtoupper(env('LASALLE_APP_NAME')) . ' LaSalle Software application.');
            $this->line("So I am exiting you out of this artisan command.");
            $this->line("exiting...");
            $this->line("You are now exited from lslibrary:lasalleinstalladminapp.");
            echo "\n\n";
            return;
        }

        $this->line('--------------------------------------------------------------------------------');
        $this->line('                       Welcome to my LaSalle Software\'s');
        $this->line('             Administrative Back-end App\' Installation Artisan Command!');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  You are installing the ' . mb_strtoupper(env('LASALLE_APP_NAME')) . ' LaSalle Software Application.');
        echo "\n";
        $this->line('  You are installing to your ' . $this->getLaravel()->environment() . ' environment.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  This command does stuff to set up my admin app, stuff that does not happen');
        $this->line('  with my other apps: Nova preparation, optional database drop, database');
        $this->line('  migration, and database seeding.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Read my INSTALLATION.md *BEFORE* running this command.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Have you run my environment variable installation artisan command? No?');
        $this->line('  Well, then! Type "oops" to exit this artisan command, and then run');
        $this->line('  "lslibrary:lasalleinstallenv". Then, re-run this artisan command.');
        $this->line('--------------------------------------------------------------------------------');
        echo "\n";
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->line('--------------------------------------------------------------------------------');
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU RUN LSLIBRARY:LASALLEINSTALLENV ALREADY?
        echo "\n\n";
        $this->alert('Did you already run lslibrary:lasalleinstallenv? You must run it first!');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "oops" to exit this artian command...)</>');
        if ($runConfirmation == strtolower("oops")) {
            $this->line('<fg=red;bg=yellow>Good stuff! Please run "php artisan lslibrary:lasalleinstallenv" and then re-run this artisan command.</>');
            $this->echoOutro();
            return;
        }
        $this->comment('ok... Let us get this show on the road...');
        // END: DID YOU RUN LSLIBRARY:LASALLEINSTALLENV ALREADY?
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU SET UP YOUR DATABASE?
        echo "\n\n\n";
        $this->alert('Did you already set up your database, and double check that the DB vars are set in .env?');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "yes" to continue)</>');
        if ($runConfirmation != strtolower("yes")) {
            $this->line('<fg=red;bg=yellow>OK, you want to set up your DB, and check your vars in .env, so I am NOT going to continue running this command. Bye!</>');
            $this->echoOutro();
            return;
        }
        $this->comment('ok... you said that you want to continue running this command. Let us continue then...');
        // END: DID YOU SET UP YOUR DATABASE?
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line("  Now setting up Laravel Nova's first part admin package");
            $this->line('-----------------------------------------------------------------------');

            // if NOVA is not installed
            if (! class_exists('Laravel\Nova\Nova')) {
                echo "\n";
                $this->line('  <fg=red;bg=yellow>The first party Laravel Nova commercial package is not installed.');
                $this->line('  <fg=red;bg=yellow>Nova is critical to my admin app. You buy and must install Nova!');
                $this->line('  <fg=red;bg=yellow>So please install Nova, then re-run this artisan command.');
                $this->echoOutro();
                return;
            }
            // NOVA is installed, so run "php artisan nova:install"
            //echo "\n\n";
            $this->comment('Now running Laravel\'s first-party administration package "Nova" set-up...');
            //echo "\n\n";
            $this->call('nova:install');

            // delete the app/nova files because we definitely do not want this user resource showing up in our Nova menu
            //echo "\n";
            $this->deleteFile(app_path().'/Nova/Resource.php');
            $this->deleteFile(app_path().'/Nova/User.php');
            echo "\n";
            $this->comment('Finished the Nova set-up.');
        }
        // END: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DATABASE DROP, MIGRATION, AND SEEDS

        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting up the database');
        $this->line('-----------------------------------------------------------------------');

        // Set an env var to false when production
        // (probably not really necessary)
        if (strtolower($this->getLaravel()->environment()) === 'production') {
            $this->setLasallePopulateDatabaseWithTestDataToFalse();
        }

        // Drop the database tables
        echo "\n";
        $this->comment('Do you want to DROP the existing database?');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "yes" to continue)</>');
        if ($runConfirmation == strtolower("yes")) {
            $this->comment('Now about to DROP your existing database...');
            $database = $this->input->getOption('database');
            $this->dropAllTables($database);
            $this->info("Your database was dropped.");
        }

        // Migration
        echo "\n\n";
        $this->comment('Now running the database migration...');
        $this->call('migrate');
        $this->comment('Your database was migrated.');

        // Seeding
        // lslibrary:customseed checks if the blog backend is installed, and if so it migrates the blog database table
        echo "\n\n";
        $this->comment('Now running the database seeding...');
        $this->call('lslibrary:customseed');
        //$this->comment('Your database was seeded.');
        // END: DATABASE DROP, MIGRATION, AND SEEDS
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DONE!
        echo "\n\n\n";
        $this->echoOutro();
        // END: DONE!
        // -------------------------------------------------------------------------------------------------------------
    }

    /**
     * Echo the final message
     *
     * return void
     */
    protected function echoOutro()
    {
        echo "\n\n";
        $this->info('====================================================================');
        $this->info('              ** lslibrary:lasalleinstalladminapp has finished **');
        $this->info('====================================================================');
        echo "\n\n";
        return;
    }

    /**
     * Set LASALLE_POPULATE_DATABASE_WITH_TEST_DATA to false
     *
     * @return void
     */
    protected function setLasallePopulateDatabaseWithTestDataToFalse()
    {
        $envFile = file_get_contents($this->laravel->environmentFilePath());

        $pattern = $this->pattern('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true');
        $replacement = 'LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false';
        $envFile = preg_replace($pattern, $replacement, $envFile);

        $pattern = $this->pattern('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=TRUE');
        $replacement = 'LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false';
        $envFile = preg_replace($pattern, $replacement, $envFile);

        file_put_contents($this->laravel->environmentFilePath(), $envFile);
    }

    /**
     * Get the console command options.
     *
     * PASTED THIS FROM THE CUSTOMDROP COMMAND! (not a refactor candidate, need this method here!)
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
