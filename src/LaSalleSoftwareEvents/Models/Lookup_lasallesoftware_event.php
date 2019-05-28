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

namespace Lasallesoftware\Library\LaSalleSoftwareEvents\Models;

// LaSalle Software
use Lasallesoftware\Library\Common\Models\CommonModel;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for lookup_lasallesoftware_event.
 *
 * This is a lookup table,
 *
 * @package Lasallesoftware\Library\LaSalleSoftwareEvents\Models
 */
class Lookup_lasallesoftware_event extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'lookup_lasallesoftware_events';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'enabled',
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
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with uuid.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function uuid()
    {
        return $this->hasOne('Lasallesoftware\Library\UniversallyUniqueIDentifiers\Uuid');
    }
}
