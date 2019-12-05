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

// Laravel classes
use http\Exception\BadQueryStringException;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;

/**
 * Class LasalleinstallCommand
 *
 * Automate some LaSalle Software installation steps
 *
 * @package Lasallesoftware\Library\Commands\LasalleinstallCommand
 */
class LasalleinstallCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:lasalleinstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command that automates some installation steps.';

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
     * Create a new config clear command instance.
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
        // START: INTRO
        echo "\n\n";
        $this->info('====================================================================');
        $this->info('              ** starting lslibrary:lasalleinstall **');
        $this->info('====================================================================');
        echo "\n\n";

        $this->line('-----------------------------------------------------------------------');
        $this->line('  Welcome to my LaSalle Software\'s Installation Command!');
        echo "\n";
        $this->line('  Since my command utility does *not* do every installation step, please');
        $this->line('  read my INSTALLATION.md before running this command.');
        echo "\n";
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->line('-----------------------------------------------------------------------');
        $this->line('  You are installing the ' . mb_strtoupper(env('LASALLE_APP_NAME')) . ' LaSalle Software Application.');
        echo "\n";
        $this->line('  You are installing to your ' . $this->getLaravel()->environment() . ' environment.');
        $this->line('-----------------------------------------------------------------------');
        $this->line('  My installation utility will:');
        if (env('LASALLE_APP_NAME') == 'adminbackendapp')  $this->line('  * ask about your database');
        if (env('LASALLE_APP_NAME') == 'adminbackendapp')  $this->line('  * finish installing Laravel\'s Nova package');
        $this->line('  * ask about some parameters in .env');
        if (env('LASALLE_APP_NAME') == 'adminbackendapp')  $this->line('  * run the database migration');
        if (env('LASALLE_APP_NAME') == 'adminbackendapp')  $this->line('  * run the database seeding');
        $this->line('-----------------------------------------------------------------------');
        // END: INTRO


        // START: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        echo "\n\n\n";
        $this->alert('Are you absolutely sure that you want to run this command?');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "yes" to continue)</>');
        if ($runConfirmation != strtolower("yes")) {
            $this->line('<fg=red;bg=yellow>OK, you did not type "yes", so I am NOT going to continue running this command. Bye!</>');
            $this->echoOutro();
            return;
        }
        $this->comment('ok... you said that you want to continue running this command. Let us continue then...');
        // END: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?


        // START: THE adminbackendapp NEEDS A DATABASE
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            // did you set up the database?
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  YOUR DATABASE');
            $this->line('-----------------------------------------------------------------------');
            $this->line("  My install utility does *not* set up your database.");
            $this->line("  So, you must set up your db before running this install utility. ");
            $this->line('-----------------------------------------------------------------------');
            $this->line("   If you have *not* yet set up your database in your .env, then:");
            $this->line("   i)   exit this installation command line utility");
            $this->line("   ii)  set up your database in your .env");
            $this->line("   iii) re-run this installation command line utility");
            $this->line('-----------------------------------------------------------------------');
            echo "\n";

            $this->comment('******************************');
            $this->comment('  <fg=yellow>* Is your database set up? *</>');
            $this->comment('******************************');
            $runConfirmation = $this->ask('<fg=yellow>(y/n)</>');
            if ($runConfirmation != strtolower('y')){
                $this->line('  <fg=red;bg=yellow>You have NOT set up your database.</>');
                $this->line('  <fg=red;bg=yellow>So go set up your database, and then re-run my installation utility.</>');
                $this->echoOutro();
                return;
            }

            $this->comment('ok... you said that your database is set up properly. Let us continue then...');
            echo "\n\n";
            // END: THE adminbackendapp NEEDS A DATABASE
        }


        // START: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  LARAVEL\'s FIRST PARTY NOVA ADMINISTRATION PACKAGE');
            $this->line('-----------------------------------------------------------------------');

            // if NOVA is not installed
            if (! class_exists('Laravel\Nova\Nova')) {
                echo "\n";
                $this->line('  <fg=red;bg=yellow>Nova is not installed. You must install Nova!');
                $this->line('  <fg=red;bg=yellow>So install Nova, then re-run my installation utilty.');
                $this->echoOutro();
                return;
            }

            echo "\n";
            $this->comment('ok... you said that your database is set up properly. Let us continue then...');

            // NOVA is installed, so run "php artisan nova:install"
            echo "\n\n";
            $this->comment('Now running the Laravel first-party administration package "Nova" set-up...');
            echo "\n\n";
            $this->call('nova:install');

            // delete the app/nova files because we definitely do not want this user resource showing up in our Nova menu
            echo "\n";
            $this->deleteFile(app_path().'/Nova/Resource.php');
            $this->deleteFile(app_path().'/Nova/User.php');
            echo "\n\n";
            $this->comment('Finished the Nova set-up.');
        }
        // END: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE


        // START: SET THE PARAMS IN .ENV

        // SET APP_NAME
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  YOUR .ENV PARAMETERS');
        $this->line('-----------------------------------------------------------------------');


        echo "\n\n";
        $this->comment('****************************');
        $this->comment('  <fg=yellow>* What is your APP_NAME *</>');
        $this->comment('****************************');
        $this->comment('An example is: LaSalle Software Administration App');
        $this->comment('  <fg=yellow>(to leave the APP_NAME as it is already, just hit enter)</>');
        $appName = $this->ask('<fg=yellow>(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)</>');
        $this->comment('You typed "' . $appName . '".');
        $this->comment('Setting APP_NAME in .env to "'. $appName . '"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppName', $appName, true);
        $this->comment('Finished setting your APP_NAME in .env to "' . $appName . '"');

        // SET APP_URL
        echo "\n\n";
        $this->comment('****************************');
        $this->comment('  <fg=yellow>* What is your APP_URL *</>');
        $this->comment('****************************');
        $this->comment('An example is: https://lasallesoftware.ca');
        $this->comment('MUST start with "http://" or "https://"');
        $this->comment('  <fg=yellow>(to leave the APP_NAME as it is already, just hit enter)</>');
        $appURL = $this->ask('<fg=yellow>(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)</>');;
        $this->comment('You typed "' . $appURL . '"');
        $this->comment('Setting APP_URL in .env to "'. $appURL . '"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppURL', $appURL, false);
        $this->comment('Finished setting APP_URL in .env to "' . $appURL . '"');

        // SET LASALLE_APP_DOMAIN_NAME
        echo "\n\n";
        $lasalleAppDomainName = $this->getLasalleAppDomainName($appURL);
        $this->comment('Setting LASALLE_APP_DOMAIN_NAME in .env to "'. $lasalleAppDomainName . '""...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleAppDomainName', $lasalleAppDomainName, false);
        $this->info('Finished setting LASALLE_APP_DOMAIN_NAME in .env to "' . $lasalleAppDomainName . '"');

        // END: SET THE PARAMS IN .ENV






        // START: THE adminbackendapp NEEDS THE DATABASE MIGRATIONS AND SEEDS
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            // WHEN PRODUCTION, SET LASALLE_POPULATE_DATABASE_WITH_TEST_DATA TO FALSE (yeah, not really necessary.. "belt and suspenders" thing)
            if (strtolower($this->getLaravel()->environment()) === 'production') $this->setLasallePopulateDatabaseWithTestDataToFalse();

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  DATABASE MIGRATION');
            $this->line('-----------------------------------------------------------------------');

            echo "\n\n";
            $this->comment('*********************************');
            $this->comment('  <fg=yellow>* About to run the database migration *</>');
            $this->comment('*********************************');
            $this->ask('<fg=yellow>press any key to continue...</>');
            $this->comment('Now running the database migration...');
            $this->call('migrate');
            $this->comment('Finished the database migration.');

            // lslibrary:customseed checks if the blog backend is installed, and if so it migrates the blog database table
            echo "\n\n";
            $this->comment('*********************************');
            $this->comment('  <fg=yellow>* About to run the database seeding *</>');
            $this->comment('*********************************');
            $this->ask('<fg=yellow>press any key to continue...</>');
            $this->comment('Now running the database seeding...');
            $this->call('lslibrary:customseed');

            // optional blog back-end
            if (class_exists('Lasallesoftware\Blogbackend\Version')) {
                echo "\n\n";
                $this->call('lsblogbackend:blogcustomseed');
            }
            $this->comment('Finished the database seeding.');
        }
        // END: THE adminbackendapp NEEDS THE DATABASE MIGRATIONS AND SEEDS



        // C'EST FINI
        echo "\n\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Congratulations! You finished the installation!');
        $this->line('-----------------------------------------------------------------------');
        $this->echoOutro();
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
        $this->info('              ** lslibrary:lasalleinstall has finished **');
        $this->info('====================================================================');
        echo "\n\n";
        return;
    }

    /**
     * Delete the given file
     *
     * @param  text  $file   The path and filename of the file to be deleted
     * @return void
     */
    protected function deleteFile($file)
    {
        if ($this->fileExists($file)) {
            $this->files->delete($file);
            $this->comment('Deleted ' . $file);
        }
    }

    /**
     * Determine if the file already exists.
     *
     * @param  string  $fileName
     * @return bool
     */
    protected function fileExists($fileName)
    {
        return $this->files->exists($fileName);
    }


    /**
     * @param  text $patternToSearchFor         The text being searched
     * @param  text $envFileDummyKey            The dummy key to be replaced in .env
     * @param  bool $useQuotesInTheReplacement  Do you want to use quotes in the replacement string?
     * @return void
     */
    protected function writeEnvironmentFileWithNewKey($patternToSearchFor, $envFileDummyKey, $useQuotesInTheReplacement = true)
    {
        $envFile = file_get_contents($this->laravel->environmentFilePath());

        $pattern = $this->pattern($patternToSearchFor);

        $replacement = $useQuotesInTheReplacement ? "'" . $envFileDummyKey . "'" : $envFileDummyKey;

        $envFile = preg_replace($pattern, $replacement, $envFile);

        file_put_contents($this->laravel->environmentFilePath(), $envFile);
    }

    /**
     * Return the pattern (being searched) for the preg_replace
     *
     * @param  string  $patternToSearchFor  The text being searched
     * @return string
     */
    protected function pattern($patternToSearchFor)
    {
        $delimiter = '/';

        return $delimiter . $patternToSearchFor . $delimiter;
    }

    /**
     * Return the LASALLE_APP_DOMAIN_NAME, which is based on the APP_URL.
     *
     * The APP_URL *must* start with "http://" or "https://". However, if it does not, the APP_URL is returned,
     * just so something is returned.
     *
     * @param  text   $appURL   The APP_URL
     * @return string
     */
    protected function getLasalleAppDomainName($appURL)
    {
        if (substr($appURL,0,7) == "http://") {
            return substr($appURL, 7, strlen($appURL));
        }

        if (substr($appURL,0,8) == "https://") {
            return substr($appURL, 8, strlen($appURL));
        }

        return $appURL;
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
}
