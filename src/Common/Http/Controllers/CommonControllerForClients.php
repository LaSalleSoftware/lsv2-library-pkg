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
use Lasallesoftware\Blogfrontend\JWT\Factory;

// Laravel Framework
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Third party classes
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CommonControllerForClients extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Lasallesoftware\Blogfrontend\JWT\Factory
     */
    protected $factory;


    /**
     * DisplaySinglePostController constructor.
     *
     * @param  Lasallesoftware\Blogfrontend\JWT\Factory  $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function xx($uuidComment)
    {
        $token = $this->factory->createJWT($uuidComment);

        $headers = [
            'Authorization'   => 'Bearer ' . $token,
            'InstalledDomain' => 1,
            'Accept'          => 'application/json',
        ];


        //$apiUrl = (substr(env('LASALLE_JWT_AUD_CLAIM'), 0, 8) == 'https://') ? env('LASALLE_JWT_AUD_CLAIM') : 'https://' . env('LASALLE_JWT_AUD_CLAIM');

        $apiUrl = (substr(env('LASALLE_JWT_AUD_CLAIM'), 0, 7) == 'https//') ? env('LASALLE_JWT_AUD_CLAIM') : 'http://' . env('LASALLE_JWT_AUD_CLAIM');

        $client = new Client();

        return $client->request('GET', $apiUrl . ':8888/api/v1/singlearticleblog', [
            'headers'         => $headers,
            'connect_timeout' => 10,
        ]);

    }
}
