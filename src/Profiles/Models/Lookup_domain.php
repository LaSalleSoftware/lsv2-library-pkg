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

namespace Lasallesoftware\Library\Profiles\Models;

// LaSalle Software
use Lasallesoftware\Library\Common\Models\CommonModel;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for lookup_domain.
 *
 * This is a lookup table.
 *
 * @package Lasallesoftware\Library\Profiles\Models
 */
class Lookup_domain extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'lookup_domains';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'enabled'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * LaSalle Software handles the created_at and updated_at fields, so false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'locked_at'  => 'datetime',
    ];


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many relationship with personbydomain.
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
        return $this->hasMany('\Lasallesoftware\Library\Authentication\Models\Personbydomain');
    }

    /*
     * One to many relationship with login.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function login()
    {
        return $this->hasMany('\Lasallesoftware\Library\Authentication\Models\Login');
    }
}
