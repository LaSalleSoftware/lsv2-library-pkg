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

namespace Lasallesoftware\Library\Database\DatabaseSeeds\InstalledDomains;

// use a parameter per https://laravel.com/docs/5.7/seeding#running-seeders:
// php artisan db:seed --class=Lasallesoftware\\Library\\DatabaseSeeds\\DatabaseSeeder


// Well, the things turned out, I ended up refactoring the Lookup_domains table while working on the blog packages.
// So, I ended up testing the refactored installed_domains table with the blogging test data. It probably does not matter,
// but just in case it does matter, I am recreating the blog's test data here.


use Lasallesoftware\Library\Database\DatabaseSeeds\BaseSeeder;
use Lasallesoftware\Library\Database\DatabaseSeeds\InstalledDomains\SetupUsersSeeder;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SetupUsersSeeder::class,
            SetupTestDomainsSeeder::class,
        ]);
    }
}
