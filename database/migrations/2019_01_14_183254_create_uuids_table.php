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

// LaSalle Software
use Lasallesoftware\Library\Database\Migrations\BaseMigration;

// Laravel classes
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateUuidsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "uuids";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ((!Schema::hasTable($this->tableName)) &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {

            Schema::create($this->tableName, function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('lasallesoftware_event_id')->unsigned();
                $table->foreign('lasallesoftware_event_id')->references('id')->on('lookup_lasallesoftware_events');

                $table->uuid('uuid');

                $table->string('comments')->nullable();

                $table->timestamp('created_at')->useCurrent();
                $table->integer('created_by')->unsigned()->default(1);
                $table->foreign('created_by')->references('id')->on('persons');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key constraints or these DROPs will not work
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::table('uuids', function($table){
            $table->dropForeign('uuids_lasallesoftware_event_id_foreign');
            $table->dropForeign('uuids_created_by_foreign');
        });
        Schema::dropIfExists('uuids');

        // Enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
