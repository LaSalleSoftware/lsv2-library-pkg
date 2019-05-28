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
use Lasallesoftware\Library\Nova\Fields\BaseField;

use Exception;
use DateTimeInterface;

/**
 * Class CustomDate
 *
 * The Nova Date class with customizations for display formats
 *
 * @package Lasallesoftware\Library\Nova\Fields
 */
class CustomDate extends BaseField
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'date';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {

        parent::__construct($name, $attribute, $resolveCallback ?? function ($value) {

                if (! $value instanceof DateTimeInterface) {
                    throw new Exception(__('lasallesoftwarelibrary::general.exception_message_date_cast'));
                }
            }
        );

        $this->formatTheValueForTheFormWeAreOn($this->identifyForm());

        $this->specifyShowOnForms();

        $this->withMeta(['placeholder' => $attribute ?? __('lasallesoftwarelibrary::general.not_specified')]);
    }

    /**
     * Set the first day of the week.
     *
     * @param  int  $day
     * @return $this
     */
    public function firstDayOfWeek($day)
    {
        return $this->withMeta([__FUNCTION__ => $day]);
    }

    /**
     * Set the date format (Moment.js) that should be used to display the date.
     *
     * @param  string  $format
     * @return $this
     */
    public function format($format)
    {
        return $this->withMeta([__FUNCTION__ => $format]);
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
                return $value->format('l F dS, Y');
            };

        }

        // if we are creating a new record
        if  ($formType == "creation") {

            return $this->resolveCallback = function ($value) {
                return $value->format('Y-m-d');
            };

        }

        // if we are on the detail (show) form
        if ($formType == "detail") {

            return $this->resolveCallback = function ($value) {
                return $value->format('l F dS, Y');
            };

        }

        // if we are on the update (edit) form
        if ($formType == "update") {

            return $this->resolveCallback = function ($value) {
                    return $value->format('Y-m-d');
            };
        }
    }
}
