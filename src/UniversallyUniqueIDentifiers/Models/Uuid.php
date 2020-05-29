<?php

/**
 * This file is part of the Lasalle Software library package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

namespace Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models;

// LaSalle Software
use Lasallesoftware\Library\Common\Models\CommonModel;

// Laravel Framework
use Illuminate\Support\Carbon;

/**
 * This is the model class for uuid.
 *
 * @package Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models
 */
class Uuid extends CommonModel
{
    /**
     * The database table used by the model.
     *
     * The convention is plural -- and plural is assumed.
     *
     * Lowercase.
     *
     * @var string
     */
    public $table = 'uuids';

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
     * Indicates if the model should be timestamped.
     *
     * This database table does not have an "updated_at" field, so false.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one (inverse) relationship with lookup_lasallesoftware_event.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function lookup_lasallesoftware_event()
    {
        return $this->belongsTo('Lasallesoftware\Library\LaSalleSoftwareEvents\Lookup_lasallesoftware_event');
    }

    /*
     * One to one (inverse) relationship with contact_form.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function contact_form()
    {
        return $this->belongsTo('Lasallesoftware\Contentform\Models\Contact_form');
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        OTHER METHODS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Delete expired UUIDs
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subDays($this->daysToExpiration());

        $this->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * How many days until expiration?
     *
     * @return int
     */
    protected function daysToExpiration()
    {
        return config('lasallesoftware-library.uuid_number_of_days_until_expiration');
    }
}
