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
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

namespace Lasallesoftware\Library\Dusk;

// LaSalle
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

/**
 * Trait LaSalleProvidesBrowser
 *
 * YOU NEED TO USE THIS TRAIT IN YOUR DuskTestCase IN ORDER TO USE MY TRIX.
 *
 * @package Lasallesoftware\Library\Dusk
 */
trait LaSalleProvidesBrowser
{
    /**
     * Create a new Browser instance.
     *
     * THIS METHOD OVER-RIDES Laravel\Dusk\Concerns\ProvidesBrowser::newBrowser() SO THAT
     * MY CUSTOM BROWSER INSTANCE IS USED INSTEAD OF THE DEFAULT BROWSER INSTANCE
     * (https://github.com/laravel/dusk/blob/5.0/src/Concerns/ProvidesBrowser.php#L111)
     *
     * @param  \Facebook\WebDriver\Remote\RemoteWebDriver  $driver
     * @return \Laravel\Dusk\Browser
     */
    protected function newBrowser($driver)
    {
        return new LaSalleBrowser($driver);
    }
}
