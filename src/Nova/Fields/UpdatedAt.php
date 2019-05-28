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

// LaSalle Software class
use Lasallesoftware\Library\Nova\Fields\BaseTextField;


/**
 * Class UpdatedAt
 *
 * Although the actual created_at field is of type datetime, Nova offers features specific to the text type,
 * that are not available with the datetime field type. So, I am using the text field type.
 *
 * @package Lasallesoftware\Library\Nova\Fields
 */
class UpdatedAt extends BaseTextField
{
    /**
     * The field's vue component.
     *
     * @var string
     */
    public $component = 'text-field';

    /**
     * Create a new custom text field for updated_by
     *
     * @param  string $name
     * @param  string|null $attribute
     * @param  mixed|null $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->name = __('lasallesoftwarelibrary::general.field_name_updated_at');

        $this->nullable();

        $this->formatTheValueForTheFormWeAreOn($this->identifyForm());

        $this->specifyShowOnForms();

        $this->setReadOnlyAttribute(true);
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
        $this->showOnCreation = false;
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

            $this->formatDatetimeTextField();

        }

        // if we are creating a new record
        if  ($formType == "creation") {

            // not applicable

        }

        // if we are on the detail (show) form
        if ($formType == "detail") {

            $this->formatDatetimeTextField();
        }

        // if we are on the update (edit) form
        if ($formType == "update") {

            $this->hydrateFieldWithTheCurrentDatetime();

        }
    }
}
