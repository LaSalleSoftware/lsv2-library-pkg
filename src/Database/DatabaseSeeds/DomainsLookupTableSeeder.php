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

// Third party class
use Illuminate\Support\Carbon;;

class DomainslookupTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lookup_domains')->insert([
            'title'       => app('config')->get('lasallesoftware-library.lasalle_app_domain_name'),
            'description' => app('config')->get('lasallesoftware-library.lasalle_app_domain_name'),
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        if (app('config')->get('app.env') == "testing") {
            DB::table('lookup_domains')->insert([
                'title'       => 'https://lasallesoftware.ca',
                'description' => 'LaSalleSoftware.ca',
                'enabled'     => 1,
                'created_at'  => Carbon::now(),
                'created_by'  => 1,
                'updated_at'  => null,
                'updated_by'  => null,
                'locked_at'   => null,
                'locked_by'   => null,
            ]);
        }
    }
}
