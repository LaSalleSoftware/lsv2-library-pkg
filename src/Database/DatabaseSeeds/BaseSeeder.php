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

namespace Lasallesoftware\Library\Database\DatabaseSeeds;

// Laravel Framework
use Illuminate\Database\Seeder;

// Third party classes
use Illuminate\Support\Carbon;;

class BaseSeeder extends Seeder
{
    /**
     * Yes or no: populate the database with test data?
     *
     * @return bool
     */
    public function doPopulateWithTestData()
    {
        // do not want test data in the production database, regardless of the override
        if (app('config')->get('app.env') == "production") {
            return false;
        }

        // well, if it's the testing environment, then of course we want the test data in the database
        if (app('config')->get('app.env') == "testing") {
            return true;
        }

        // if there's an override, and we're not in the production environment, then we want the test data in the db
        if (env('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA', false)) {
            return true;
        }

        return false;
    }

}
