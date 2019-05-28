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

namespace Lasallesoftware\Library\Authentication\Models;

// Laravel facades
use Illuminate\Support\Facades\DB;

// Laravel classes
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This is the model class for personbydomain.
 *
 * This is the table for logging into the app.
 *
 * @package Lasallesoftware\Library\Authentication\Models
 */
class Personbydomain extends Authenticatable
{
    use Notifiable;


    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'personbydomains';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'person_id',
        'person_first_name',
        'person_surname',
        'email',
        'lookup_domain_id',
        'lookup_domain_title',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many (inverse) relationship with persons.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function person()
    {
        return $this->belongsTo('Lasallesoftware\Library\Profiles\Models\Person');
    }

    /*
     * One to many (inverse) relationship with lookup_domain.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function lookup_domain()
    {
        return $this->belongsTo('Lasallesoftware\Library\Authentication\Models\Lookup_domain');
    }

    /*
    * One to many (inverse) relationship with email.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function email()
    {
        return $this->belongsTo('Lasallesoftware\Library\Profiles\Models\Email');
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
        return $this->hasMany('Lasallesoftware\Library\Authentication\Models\Login');
    }

    /*
    * Many to many relationship with lookup_role.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function lookup_role()
    {
        //return $this->belongsToMany('Lasallesoftware\Library\Authentication\Models\Lookup_user_group');

        return $this->belongsToMany(
            'Lasallesoftware\Library\Authentication\Models\Lookup_role',
            'personbydomain_lookup_roles',
            'personbydomain_id',
            'lookup_role_id'
        );
    }


    ///////////////////////////////////////////////////////////////////
    //////////////          LOCAL SCOPES            ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Is it the owner role?
     *
     * @return bool
     */
    public function scopeIsOwner()
    {
        return $this->lookup_role()->where('lookup_role_id', 1)->exists();
    }

    /**
     * Is it the super administrator role?
     *
     * @return bool
     */
    public function scopeIsSuperadministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 2)->exists();
    }

    /**
     * Is it the administrator role?
     *
     * @return bool
     */
    public function scopeIsAdministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 3)->exists();
    }

    /**
     * Does the current user (ie, personbydomain) have the specified role?
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role == strtolower('owner')) {
            return $this->IsOwner();
        }

        if ($role == strtolower('superadministrator')) {
            return $this->IsSuperadministrator();
        }

        if ($role == strtolower('administrator')) {
            return $this->IsAdministrator();
        }

        return false;
    }

}
