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

namespace Lasallesoftware\Library\Nova\Fields;

// Laravel facade
use Illuminate\Support\Facades\Auth;


/**
 * Class Uuid
 *
 * @package Lasallesoftware\Library\Nova\Fields
 */
class Uuid extends BaseTextField
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

        $this->name = '';   // blank so that users do not see "UUID" for the hidden field that does not display
                            // in the creation & update forms

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
        $this->showOnDetail   = false;
        $this->showOnCreation = true;
        $this->showOnUpdate   = true;

        return $this;
    }

    /**
     * Format this field for the individual forms,
     *
     * @param string  $formType  The form being displayed.
     *                           From Lasallesoftware\Library\Nova\Fields->identifyForm()
     * @param string  $uuid
     * @return \Closure
     */
    private function formatTheValueForTheFormWeAreOn($formType)
    {
        // if we are on the index form
        if ($formType == "index") {

            // not applicable

        }

        // if we are creating a new record
        if ($formType == "creation") {

            $this->doTheUuidStuff(7);
        }

        // if we are on the detail (show) form
        if ($formType == "detail") {

            // not applicable

        }

        // if we are on the update (edit) form
        if ($formType == "update") {

            $this->doTheUuidStuff(8);

        }
    }

    /**
     * Do the UUID stuff!
     *
     * @param  in     $lasallesoftware_event_id   The ID from the lookup_lasallesoftware_events db table
     * @return string
     */
    private function doTheUuidStuff($lasallesoftware_event_id = 6)
    {
        $uuid = $this->getUuid($lasallesoftware_event_id);

        $this->withMeta(['type'  => 'hidden']);
        $this->withMeta(['value' => $uuid]);
    }

    /**
     * Create the UUID
     *
     * @param  in     $lasallesoftware_event_id   The ID from the lookup_lasallesoftware_events db table
     * @return string
     */
    private function getUuid($lasallesoftware_event_id)
    {
        $lasallesoftware_event_id = $lasallesoftware_event_id;
        $uuidComment              = "Created by a Nova form";
        $uuidCreatedby            = Auth::id();

        // https://laravel.com/docs/5.8/helpers#method-resolve
        $uuidGenerator = resolve('Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator');

        return $uuidGenerator->createUuid($lasallesoftware_event_id, $uuidComment, $uuidCreatedby);
    }
}
