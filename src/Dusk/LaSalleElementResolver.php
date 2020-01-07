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
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-library-pkg
 *
 */

namespace Lasallesoftware\Library\Dusk;

use Exception;
use Laravel\Dusk\ElementResolver;

class LaSalleElementResolver extends ElementResolver
{

    /**
     * Resolve the element for a given input TRIX "field".
     *
     * I NEED TO EXTEND THE ElementResolver CLASS TO WRITE A METHOD THAT
     *
     * https://github.com/laravel/dusk/blob/5.0/src/Concerns/InteractsWithElements.php#L140
     *
     * @param  string  $field
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     * @throws \Exception
     */
    public function resolveForTypingTrix($field)
    {
        if (! is_null($element = $this->findById($field))) {
            return $element;
        }

        return $this->firstOrFail([

            // I am pretty sure that Nova v2.0.7 (.6?) added "rounded-lg" to the class
            // This one was "fun" to track down
            // (https://nova.laravel.com/releases)

            //"trix-editor[class='{$field}']",
            "trix-editor[class='{$field} rounded-lg']",
        ]);
    }
}
