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

namespace Lasallesoftware\Library\Common\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Library\Helpers\GeneralHelpers;

// Laravel Framework
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use GeneralHelpers;

    /**
     * Get the installed domain specified in the request header, stripped of the "http:\\".
     *
     * @param  Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getInstalledDomainFromTheRequest($request)
    {
        return $this->removeHttp($this->getRequestingDomainFromTheHeader($request));
    }

    /**
     * Get the installed domain specified in the request's header.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string                             Such as "hackintosh.lsv2-basicfrontend-app.com" (omit quotes).
     */
    public function getRequestingDomainFromTheHeader($request)
    {
        return $request->header('RequestingDomain');
    }
}
