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

// LaSalle Software
use Lasallesoftware\Library\Authentication\Models\Login;

// Laravel classes
use Illuminate\Console\Command;


/**
 * Class DeleteInactiveLoginsRecords
 *
 * Deletes logins database table records that have become inactive.
 *
 * This command is supposed to be run automatically via scheduler.
 *
 * @package Lasallesoftware\Library\Commands\DeleteInactiveLoginsRecordsCommand
 */
class DeleteInactiveLoginsRecordsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:deleteinactiveloginsrecords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command that deletes inactive logins db table records.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $login = new Login();
        $login->deleteInactiveLoginsRecords();

        //echo  "\n\n " . $login->deleteInactiveLoginsRecords();

        /*

        $all = Login::all();

        foreach ($all as $x) {
            echo "\n\n token and updated_at = " . $x->token . " " . $x->updated_at;
        }

        $all = Login::all();

        foreach ($all as $x) {
            echo "\n\n token and updated_at = " . $x->token . " " . $x->updated_at;
        }

        */

        echo "\n\n deleteinactiveloginsrecords is done!!!";
    }
}
