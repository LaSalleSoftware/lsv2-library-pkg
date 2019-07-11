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
use Lasallesoftware\Library\Profiles\Models\Installed_domain as Model;


//                       SPECIAL NOTE!
//
// Installed_domains is populated during seeding. If it is absolutely necessary to create/update/delete records,
// then -- gasp! -- modify the table directly. Viewing the table in the admin is just a convenience!
//


/**
 * Class Installed_domainPolicy
 *
 * @package Lasallesoftware\Library\Policies
 */
class Installed_domainPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];


    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Installed_domain      $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner') ? true: false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Installed_domain      $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        return false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Installed_domain      $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        return false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     *  This feature is not used!
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Installed_domain      $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * This feature is not used!
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Profiles\Models\Installed_domain      $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        return false;
    }
}
