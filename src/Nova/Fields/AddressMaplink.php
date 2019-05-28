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

namespace Lasallesoftware\Library\Nova\Fields;


/**
 * Class AddressMaplink
 *
 * @package Lasallesoftware\Library\Nova\Fields
 */
class AddressMaplink extends BaseTextField
{
    /**
     * Create a new custom text field for title.
     *
     * @param  string $name
     * @param  string|null $attribute
     * @param  mixed|null $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->name = __('lasallesoftwarelibrary::general.field_name_addressmaplink');

        $this->help('<ul>
                           <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
        );

        $this->formatTheValueForTheFormWeAreOn($this->identifyForm());

        $this->specifyShowOnForms();
    }

    /**
     * This field will display, or not display, on these forms.
     *
     * @return $this
     */
    private function specifyShowOnForms()
    {
        $this->showOnIndex    = false;
        $this->showOnDetail   = true;
        $this->showOnCreation = true;
        $this->showOnUpdate   = true;

        return $this;
    }

    /**
     * Format this field for the individual forms,
     *
     * @param string  $formType  The form being displayed.
     *                           From Lasallesoftware\Library\Nova\Fields->identifyForm()
     * @return \Closure
     */
    private function formatTheValueForTheFormWeAreOn($formType)
    {
        // if we are on the index form
        if ($formType == "index") {

            return $this->resolveCallback = function ($value) {

                return $value;
            };

        }

        // if we are creating a new record
        if  ($formType == "creation") {

            // not applicable
        }

        // if we are on the detail (show) form
        if ($formType == "detail") {

            $this->asHtml();

            return $this->resolveCallback = function ($value) {

                return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
            };

        }

        // if we are on the update (edit) form
        if ($formType == "update") {

            return $this->resolveCallback = function ($value) {

                return $value;
            };

        }
    }
}
