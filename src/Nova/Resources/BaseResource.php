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

namespace Lasallesoftware\Library\Nova\Resources;

// LaSalle Software classes
use Lasallesoftware\Library\Nova\Fields\CreatedAt;
use Lasallesoftware\Library\Nova\Fields\CreatedBy;
use Lasallesoftware\Library\Nova\Fields\UpdatedAt;
use Lasallesoftware\Library\Nova\Fields\UpdatedBy;

// Laravel Nova class moved to the LaSalle Software library
use Lasallesoftware\Library\Nova\Resources\NovaBaseResource;


/**
 * Class BaseResource
 *
 * @package Lasallesoftware\Library\Nova\Resources\BaseResource
 */
abstract class BaseResource extends NovaBaseResource
{
    /**
     * Display the system panel
     *
     * return array
     */
    public function systemPanel()
    {
        return [
            Heading::make( __('lasallesoftwarelibrary::general.field_heading_system_fields')),

            new Panel(__('lasallesoftwarelibrary::general.panel_system_fields'), $this->systemFields()),
        ];
    }

    /**
     * Get the system fields for this resource.
     *
     * @return array
     */
    public function systemFields()
    {
        return [
            CreatedAt::make('created_at'),
            CreatedBy::make('created_by'),

            UpdatedAt::make('updated_at'),
            UpdatedBy::make('updated_by'),
        ];
    }

    /**
     * Get the relatable query for a resource.
     *
     * This method produces the query used to populate the drop-downs for profile tables related to the
     * person and company tables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getRelatableQueryForThisResource($query)
    {
        // segment the url by explode-ing
        $currentUrl        = url()->current();
        $explodeCurrentUrl = explode('/', $currentUrl);

        // isolate the url segment
        $count   = count($explodeCurrentUrl);
        $segment = $explodeCurrentUrl[$count - 4];

        // is the profiles dropdown for the person db table, or for the company db table?
        if ($segment == 'people') {
            return $query->whereDoesntHave('person', function(){
                return;
            });
        }

        if ($segment == 'companies') {
            return $query->whereDoesntHave('company', function(){
                return;
            });
        }

        return $query;
    }
}
