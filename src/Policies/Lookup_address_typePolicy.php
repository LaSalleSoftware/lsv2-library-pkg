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

namespace Lasallesoftware\Library\Policies;

// LaSalle Software class
use Lasallesoftware\Library\Common\Policies\CommonPolicy;
use Lasallesoftware\Library\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Library\Profiles\Models\Lookup_address_type as Model;


/**
 * Class Lookup_address_typePolicy
 *
 * @package Lasallesoftware\Library\Policies
 */
class Lookup_address_typePolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [1,2,3,4,5];


    /**
     * Determine whether the user can view a lookup_address_type's details.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can create a lookup_address_type.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can update a lookup_address_type.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        if (!$user->hasRole('owner')) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete a lookup_address_type.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        if (!$user->hasRole('owner')) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore a lookup_address_type.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can permanently delete a lookup_address_type.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        if (!$user->hasRole('owner')) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }
}
