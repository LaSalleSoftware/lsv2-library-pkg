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

namespace Lasallesoftware\Library;

// Laravel class
use Illuminate\Support\Facades\Gate;

/**
 * Trait LibraryPoliciesServiceProvider
 *
 * Adapted from https://github.com/laravel/framework/blob/5.8/src/Illuminate/Foundation/Support/Providers/AuthServiceProvider.php
 *
 * @package Lasallesoftware\Library
 */
trait LibraryPoliciesServiceProvider
{
    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies() as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [
            'Lasallesoftware\Library\Profiles\Models\Lookup_address_type'   => 'Lasallesoftware\Library\Policies\Lookup_address_typePolicy',
            'Lasallesoftware\Library\Profiles\Models\Installed_domain'      => 'Lasallesoftware\Library\Policies\Installed_domainPolicy',
            'Lasallesoftware\Library\Profiles\Models\Lookup_email_type'     => 'Lasallesoftware\Library\Policies\Lookup_email_typePolicy',
            'Lasallesoftware\Library\LaSalleSoftwareEvents\Models\Lookup_lasallesoftware_event' => 'Lasallesoftware\Library\Policies\Lookup_lasallesoftware_eventPolicy',
            'Lasallesoftware\Library\Authentication\Models\Lookup_role'     => 'Lasallesoftware\Library\Policies\Lookup_rolePolicy',
            'Lasallesoftware\Library\Profiles\Models\Lookup_social_type'    => 'Lasallesoftware\Library\Policies\Lookup_social_typePolicy',
            'Lasallesoftware\Library\Profiles\Models\Lookup_telephone_type' => 'Lasallesoftware\Library\Policies\Lookup_telephone_typePolicy',
            'Lasallesoftware\Library\Profiles\Models\Lookup_website_type'   => 'Lasallesoftware\Library\Policies\Lookup_website_typePolicy',

            'Lasallesoftware\Library\Profiles\Models\Address'               => 'Lasallesoftware\Library\Policies\AddressPolicy',
            'Lasallesoftware\Library\Profiles\Models\Email'                 => 'Lasallesoftware\Library\Policies\EmailPolicy',
            'Lasallesoftware\Library\Profiles\Models\Social'                => 'Lasallesoftware\Library\Policies\SocialPolicy',
            'Lasallesoftware\Library\Profiles\Models\Telephone'             => 'Lasallesoftware\Library\Policies\TelephonePolicy',
            'Lasallesoftware\Library\Profiles\Models\Website'               => 'Lasallesoftware\Library\Policies\WebsitePolicy',

            'Lasallesoftware\Library\Profiles\Models\Company'               => 'Lasallesoftware\Library\Policies\CompanyPolicy',
            'Lasallesoftware\Library\Profiles\Models\Person'                => 'Lasallesoftware\Library\Policies\PersonPolicy',

            'Lasallesoftware\Library\Authentication\Models\Personbydomain'  => 'Lasallesoftware\Library\Policies\PersonbydomainPolicy',

        ];
    }
}
