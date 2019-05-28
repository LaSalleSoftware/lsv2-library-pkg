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
use Illuminate\Support\Facades\DB;

class LaSalleSoftwareEventTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 1,
            'title'       => 'Data seeding during installation',
            'description' => 'this_record_created_during_initial_seeding',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 2,
            'title'       => 'No Event Specified',
            'description' => 'No LaSalle Software event was specified when a new UUID was requested',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 3,
            'title'       => 'Created during testing',
            'description' => 'Created during testing. Do not use in production',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 4,
            'title'       => 'Login Form',
            'description' => 'Attempt to Login coming from the Login Form',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 5,
            'title'       => 'Register Form',
            'description' => 'Attempt to register coming from the Register Form',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 6,
            'title'       => 'Nova Form',
            'description' => 'Initiated by a Nova Form',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 7,
            'title'       => 'Nova Creation Form',
            'description' => 'Initiated by a Nova Creation Form',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_lasallesoftware_events')->insert([
            'id'          => 8,
            'title'       => 'Nova Update Form',
            'description' => 'Initiated by a Nova Update Form',
            'enabled'     => 1,
            'created_at'  => now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);
    }
}
