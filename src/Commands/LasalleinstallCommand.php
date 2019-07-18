<?php

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
        echo "\n\n====================================================================\n";
        echo "              ** lslibrary:lasalleinstall is starting **";
        echo "\n====================================================================\n";

        echo "\n-----------------------------------------------------\n";
        echo   "Welcome to my LaSalle Software's Installation Command!";
        echo  "\n\n";
        echo  "This command does *not* do every installation step,";
        echo  "so please read INSTALLATION.md before running this command.";
        echo "\n-----------------------------------------------------\n";



        // START: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        $this->echoBlankLine();
        $this->info('Are you absolutely sure that you want to run this command?');
        $runConfirmation = $this->ask('(type "yes" to continue)');
        if ($runConfirmation != strtolower("yes")) {
            $this->error('OK, you did not type "yes", so I am *not* going to continue running this command. Bye!');
            $this->echoOutro();
            return;
        }
        $this->info('ok... you said that you want to continue running this command. Let us continue then...');
        // END: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?


        // START: THE adminbackendapp NEEDS A DATABASE
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            // did you set up the database?
            $this->echoBlankLine();
            $this->info('-----------------------------------------------------------------------');
            $this->info("** You are installing LaSalle Software's Administrative Back-end App **");
            $this->info('-----------------------------------------------------------------------');
            echo "\n";
            $this->info('My Back-end App needs a database.');
            if (! $this->ask('Is your database set up (y/n)?')) {
                $this->error('You have yet to set up your database for this Back-end app.');
                $this->error('Please set up your database, and then re-run this installation command');
                $this->echoOutro();
                return;
            }

            // check if using the Laravel db defaults
            if (
                (env('DB_DATABASE') == 'homestead') &&
                (env('DB_USERNAME') == 'homestead') &&
                (env('DB_PASSWORD') == 'secret')
            ) {
                $this->error('**HOLD ON THERE!** You are using the Laravel database default database, username, and password');
                if (!$this->ask('Are you absolutely sure that your database is set up correctly (y/n)?')) {
                    $this->error('You are not sure if your database is set up. Thank you for double checking');
                    $this->echoBlankLine();
                    $this->line('Please double check that your database is properly set up, and then re-run this installation command');
                    $this->echoOutro();
                    return;
                }
            }

            $this->info('ok... you said that your database is set up properly. Let us continue then...');
            // END: THE adminbackendapp NEEDS A DATABASE
        }


        // START: SET THE PARAMS IN .ENV

        // SET APP_NAME
        $this->echoBlankLine();
        echo "-----------------------------------------------------\n";
        $this->info('What is your APP_NAME?');
        $this->info('An example is: LaSalle Software Administration App');
        $appName = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');
        $this->info(' ..you typed "' . $appName . '"');
        $this->info(' ..setting APP_NAME in .env to "'. $appName . '"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppName', $appName, true);
        $this->info(' ..completed setting your APP_NAME in .env to "' . $appName . '"');

        // SET APP_URL
        $this->echoBlankLine();
        echo "-----------------------------------------------------\n";
        $this->info('What is your APP_URL?');
        $this->info('An example is: https://lasallesoftware.ca');
        $this->info('MUST start with "http://" or "https://"');
        $appURL = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');;
        $this->info(' ..you typed "' . $appURL . '"');
        $this->info(' ..setting APP_URL in .env to "'. $appURL . '"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppURL', $appURL, false);
        $this->info(' ..completed setting APP_URL in .env to "' . $appURL . '"');

        // SET LASALLE_APP_DOMAIN_NAME
        $this->echoBlankLine();
        $lasalleAppDomainName = $this->getLasalleAppDomainName($appURL);
        $this->info(' ..setting LASALLE_APP_DOMAIN_NAME in .env to "'. $lasalleAppDomainName . '""...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleAppDomainName', $lasalleAppDomainName, false);
        $this->info(' ..completed setting LASALLE_APP_DOMAIN_NAME in .env to "' . $lasalleAppDomainName . '"');

        // END: SET THE PARAMS IN .ENV



        // START: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            // is NOVA is installed?;
            if (! class_exists('Laravel\Nova\Nova')) {
                $this->echoBlankLine();
                $this->error('Laravel Nova is not installed. You must install Nova!');
                $this->error('Please install Nova, then re-run this installation command.');
                $this->echoOutro();
                return;
            }


            // NOVA is installed, so run "php artisan nova:install"
            $this->echoBlankLine();
            echo "-----------------------------------------------------\n";
            $this->info(' ..running the Laravel first-party administration package "Nova" set-up...');
            $this->call('nova:install');

            // delete the app/nova files because we definitely do not want this user resource showing up in our Nova menu
            $this->echoBlankLine();
            $this->deleteFile(app_path().'/Nova/Resource.php');
            $this->deleteFile(app_path().'/Nova/User.php');
            $this->info(' ..completed the Nova set-up.');
        }
        // END: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE


        // START: THE adminbackendapp NEEDS THE DATABASE MIGRATIONS AND SEEDS
        if (env('LASALLE_APP_NAME') == 'adminbackendapp') {

            // SET LASALLE_POPULATE_DATABASE_WITH_TEST_DATA to false AS A BELT-AND-SUSPENDERS SAFETY THING
            $this->setLasallePopulateDatabaseWithTestDataToFalse();

            $this->echoBlankLine();
            echo "-----------------------------------------------------";
            $this->ask('About to run the database migration -- press any key to continue');
            $this->info('  ..running the database migration...');
            $this->call('migrate');
            $this->info(' ..completed the database migration.');

            // lslibrary:customseed checks if the blog backend is installed, and if so it migrates the blog database table
            $this->echoBlankLine();
            echo "-----------------------------------------------------";
            $this->ask('About to run the database seeding -- press any key to continue');
            $this->info('  ..running the database seeding...');
            $this->call('lslibrary:customseed');

            // optional blog back-end
            if (class_exists('Lasallesoftware\Blogbackend\Version')) {
                $this->echoBlankLine();
                $this->call('lsblogbackend:blogcustomseed');
            }
            $this->info(' ..completed the database seeding.');
        }
        // END: THE adminbackendapp NEEDS THE DATABASE MIGRATIONS AND SEEDS



        // C'EST FINI
        $this->echoOutro();
    }

    /**
     * Echo a blank line
     *
     * @return string
     */
    protected function echoBlankLine()
    {
        return $this->info("\n");
    }

    /**
     * Echo the final message
     *
     * return void
     */
    protected function echoOutro()
    {
        echo "\n\n====================================================================\n";
        echo "              ** lslibrary:lasalleinstall is finished **";
        echo "\n====================================================================\n\n";

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
            $this->info(' ..completed deleting ' . $file);
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
