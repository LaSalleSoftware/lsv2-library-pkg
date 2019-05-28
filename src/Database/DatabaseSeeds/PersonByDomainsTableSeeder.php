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
use Illuminate\Support\Str;

// Third party classes
use Illuminate\Support\Carbon;;

class PersonByDomainsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for the owner role
        $person = $this->getPerson(2);
        DB::table('personbydomains')->insert([
            'person_id'             => $person->id,
            'person_first_name'     => $person->first_name,
            'person_surname'        => $person->surname,
            'email'                 => $person->email[0]->email_address,
            'email_verified_at'     => Carbon::now(),
            'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'lookup_domain_id'      => 1,
            'lookup_domain_title'   => $this->getDomainTitle(),
            'uuid'                  => 'created_during_initial_seeding',
            'created_at'            => Carbon::now(),
            'created_by'            => 1,
            'updated_at'            => null,
            'updated_by'            => null,
            'locked_at'             => null,
            'locked_by'             => null,
        ]);

        // populate the person_email pivot table with the above email address
        DB::table('personbydomain_lookup_roles')->insert([
            'id'                => 1,
            'personbydomain_id' => 1,
            'lookup_role_id'    => 1,
        ]);


        if (app('config')->get('app.env') == "testing") {

            // for the super administrator role
            $person = $this->getPerson(3);
            DB::table('personbydomains')->insert([
                'person_id'             => $person->id,
                'person_first_name'     => $person->first_name,
                'person_surname'        => $person->surname,
                'email'                 => $person->email[0]->email_address,
                'email_verified_at'     => Carbon::now(),
                'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'lookup_domain_id'      => 1,
                'lookup_domain_title'   => $this->getDomainTitle(),
                'uuid'                  => 'created_during_initial_seeding',
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            // populate the person_email pivot table with the above email address
            DB::table('personbydomain_lookup_roles')->insert([
                'id'                => 2,
                'personbydomain_id' => 2,
                'lookup_role_id'    => 2,
            ]);


            // for the administrator role
            $person = $this->getPerson(4);
            DB::table('personbydomains')->insert([
                'person_id'             => $person->id,
                'person_first_name'     => $person->first_name,
                'person_surname'        => $person->surname,
                'email'                 => $person->email[0]->email_address,
                'email_verified_at'     => Carbon::now(),
                'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'lookup_domain_id'      => 1,
                'lookup_domain_title'   => $this->getDomainTitle(),
                'uuid'                  => 'created_during_initial_seeding',
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            // populate the person_email pivot table with the above email address
            DB::table('personbydomain_lookup_roles')->insert([
                'id'                => 3,
                'personbydomain_id' => 3,
                'lookup_role_id'    => 3,
            ]);
        }
    }

    private function getPerson($id)
    {
        return \Lasallesoftware\Library\Profiles\Models\Person::find($id);
    }

    private function getDomainTitle()
    {
        $domain = \Lasallesoftware\Library\Profiles\Models\Lookup_domain::find(1);
        return $domain['title'];
    }
}
