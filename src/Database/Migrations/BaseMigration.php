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

namespace Lasallesoftware\Library\Database\Migrations;

// Laravel classes
use Illuminate\Database\Migrations\Migration;

class BaseMigration extends Migration
{
    /**
     * Should the migration be done?
     *
     * Only the admin app runs migrations in production
     *
     * @param  string  $app_env           Name of the environment
     * @param  string  $lasalle_app_name  Name of the LaSalle Software app
     * @return bool
     */
    public function doTheMigration($app_env, $lasalle_app_name)
    {
        // only the admin app runs migrations in production
        if (trim(strtolower($app_env)) == "production") {

            if (trim(strtolower($lasalle_app_name)) == "adminbackendapp") {
                return true;
            }

            return false;
        }

        return true;
    }
}
