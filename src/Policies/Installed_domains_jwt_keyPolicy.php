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
use Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key as Model;


class Installed_domains_jwt_keyPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];


    /**
     * Determine whether the user can view details.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can create.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can update.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can delete.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        // if person is on the "do not delete" list, then not delete-able
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can restore.
     *
     * ** NOT USE THIS FEATURE **
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * ** NOT USE THIS FEATURE **
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        return false;
    }

    /**
     * No one can attach any Installed_domain to the JWT key.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return mixed
     */
    public function attachAnyInstalled_domain(User $user, Model $model)
    {
        return false;
    }

    /**
     * No one can attach any Installed_domain to the JWT key
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return mixed
     */
    public function detachInstalled_domain(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Suppress the edit-attached button!
     *
     *
     * See this fabulous post: https://github.com/laravel/nova-issues/issues/1003#issuecomment-497008278
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain            $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Installed_domains_jwt_key $model
     * @return bool
     */
    public function attachInstalled_domain(User $user, Model $model)
    {
        return false;
    }
}
