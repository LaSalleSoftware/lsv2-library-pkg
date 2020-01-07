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

namespace Lasallesoftware\Library\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Library\Common\Models\CommonModel;

// Laravel class
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for login.
 *
 * The personbydomain records that are logged in.
 * One personbydomain can have multiple logins.
 *
 * @package Lasallesoftware\Library\Authentication\Models
 */
class Login extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'logins';

    /**
     * The attributes that aren't mass assignable.
     *
     * ['*'] is treated as "all fields are guarded"
     * https://github.com/laravel/framework/blob/5.7/src/Illuminate/Database/Eloquent/Concerns/GuardsAttributes.php#L164
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * The attributes that should be cast to native types.
     *
     * Here because the updated_at field is displayed in the Nova resource index view.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * LaSalle Software handles the created_at and updated_at fields, so false.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////         CRUD ACTIONS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Create a new logins database table record
     *
     * @param  array $data
     * @return mixed
     */
    public function createNewLoginsRecord($data)
    {
        $login = new Login;

        $login->personbydomain_id = $data['personbydomain_id'];
        $login->token             = $data['token'];
        $login->uuid              = $data['uuid'];
        $login->created_at        = Carbon::now(null);
        $login->created_by        = $data['created_by'];
        $login->updated_at        = Carbon::now(null);
        $login->updated_by        = $data['created_by'];

        if ($login->save()) {
            // Return the new ID
            return $login->id;
        }
        return false;
    }

    /**
     * Update an existing logins database table record
     *
     * @param  array $data
     * @param  model $model  The model to be updated
     * @return void
     */
    public function updateExistingLoginsRecord($data, $model)
    {
        if (! empty($data['personbydomain_id'])) {
            $model->personbydomain_id = $data['personbydomain_id'];
        }

        if ($data['token']) {
            $model->token = $data['token'];
        }

        if ($data['uuid']) {
            $model->uuid = $data['uuid'];
        }

        $model->updated_at = Carbon::now(null);
        $model->updated_by = $data['updated_by'];

        return $model->update();
    }

    /**
     * Update the "updated" fields of a specific token, using the query builder.
     *
     * No model need be passed.
     * Called by Lasallesoftware\Library\Authentication\CustomGuards\LasdalleGuard::user()
     *
     * @param  string  $token                The token!
     * @param  id      $personbydomain_id    The personbydomain_id
     * @return void
     */
    public function updateTheUpdateFieldsWithTheTokenAndUserId($token, $personbydomain_id = null)
    {
        DB::table('logins')
            ->where('token', $token)
            ->update([
                'updated_at' => Carbon::now(null),
                'updated_by' => $personbydomain_id == null ? null : $personbydomain_id,
            ]);
    }


    /**
     * Get the records for a personbydomain_id
     *
     * @param  int $personbydomain_id
     * @return collection
     */
    public function readLoginsRecordsByPersonbydomainid($personbydomain_id)
    {
        return $this->where('personbydomain_id', $personbydomain_id)->get();
    }

    /**
     * Get the record for a login token
     *
     * @param  string $loginToken
     * @return collection
     */
    public function readLoginsRecordByLogintoken($loginToken)
    {
        return $this->where('token', $loginToken)->first();
    }

    /**
     * Delete an existing logins database table record when given the model slated for deletion
     *
     * @param  model $model  The model to be deleted
     * @return void
     */
    public function deleteExistingLoginsRecordByModel($model)
    {
        $model->delete();
    }

    /**
     * Delete an existing logins database table record when given a logintoken
     *
     * @param  string $loginToken  The login token
     * @return void
     */
    public function deleteExistingLoginsRecordByLogintoken($loginToken)
    {
        $modelToDelete = $this->where('token', $loginToken)->first();
        $modelToDelete->delete();
    }

    /**
     * Delete logins records with a specific personbydomain_id
     *
     * @param  int     $personbydomainId    The personbydomain_id.
     * @return bool
     */
    public function deleteLoginsByPersonbydomainId($personbydomainId)
    {
        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //$result = $this->where('personbydomain_id', $personbydomainId)->delete();

        $result = DB::table('logins')->where('personbydomain_id', '=', $personbydomainId)->delete();


        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return $result;
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
    * One to many (inverse) relationship with personbydomain.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function personbydomain()
    {
        return $this->belongsTo('Lasallesoftware\Library\Authentication\Models\Personbydomain', 'personbydomain_id', 'id');
    }


    ///////////////////////////////////////////////////////////////////
    ///////////      DELETE INACTIVE LOGINS          //////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Delete logins records that have become inactive.
     *
     * The default config value for lasallesoftware-library.lasalle_number_of_minutes_allowed_before_deleting_the_logins_record
     * is 10 minutes.
     *
     * So, if it's been at least 10 minutes since the last request, then logged out!
     *
     * There is a unit test for this method at Tests\Unit\Library\Authentication\LoginsTable::DeleteOrphanedRecordsTest.
     *
     * There is a php artisan command for this, so it can be run in the scheduler, at
     * Lasallesoftware\Library\Commands\DeleteInactiveLoginsRecordsCommand.
     *
     * @return void
     */
    public function deleteInactiveLoginsRecords()
    {
        // How many minutes before a logins record is inactive?
        $minutesToInactivity = config(
            'lasallesoftware-library.lasalle_number_of_minutes_allowed_before_deleting_the_logins_record',
            10
        );

        // When is now?
        $now = \Carbon\Carbon::now();

        // Go through all the records, deleting inactive records.
        // Yes, I have Adam's book on higher order functions. But you know what Freud said: "sometimes a foreach is just a foreach".
        foreach ($this->all() as $login) {

            // convert the updated_at date to carbon
            $updated_at = Carbon::parse($login->updated_at);

            // if the record is inactive, then delete it
            if ($updated_at->diffInMinutes($now, true) >= $minutesToInactivity) {
                $this->deleteExistingLoginsRecordByModel($login);
            }
        }
    }
}
