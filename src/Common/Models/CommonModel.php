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

namespace Lasallesoftware\Library\Common\Models;

// LaSalle Software
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel classes
use Illuminate\Database\Eloquent\Model as Eloquent;

class CommonModel extends Eloquent
{
    /**
     * Wash the URL. Called from model events, so this method is static
     *
     * @param  string  $url
     * @return string
     */
    public static function washUrl($url)
    {
        if ((substr($url,0,7) == "http://")  ||
            (substr($url,0,8) == "https://") ||
            (is_null($url))                               ||
            (trim($url) == '')
        ) {
            return $url;
        }

        return "http://" . $url;
    }

    /**
     * stripCharactersFromText1.
     *
     * Called from model events, so this method is static
     *
     * Named "1" (stripCharactersFromText1) because it is bespoke, and maybe I will want another variation later (that
     * I can then call "stripCharactersFromText2")
     *
     * Originally created for Lasallesoftware\Library\Profiles\Models\Telephone
     *
     * @param  string  $text
     * @return string
     */
    public static function stripCharactersFromText1($text)
    {
        return str_replace(['(', ')', '-', '_', '{', '}', '[', ']'], '', $text);
    }
}
