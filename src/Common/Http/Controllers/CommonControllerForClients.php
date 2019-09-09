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
use Illuminate\Support\MessageBag;

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
     * The message bag instance.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $messages;


    /**
     * DisplaySinglePostController constructor.
     *
     * @param  Lasallesoftware\Blogfrontend\JWT\Factory  $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Send a request to the LaSalle administrative back-end.
     *
     * @param  string  $uuidComment
     * @param  string  $path
     * @param  string  $slug
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequestToLasalleBackend($uuidComment, $path, $slug = null)
    {
        $token = $this->factory->createJWT($uuidComment);

        $headers = [
            'Authorization'    => 'Bearer ' . $token,
            'RequestingDomain' => env('LASALLE_APP_DOMAIN_NAME'),
            'Accept'           => 'application/json',
        ];

        $apiUrl = (substr(env('LASALLE_JWT_AUD_CLAIM'), 0, 7) == 'https//') ? env('LASALLE_JWT_AUD_CLAIM') : 'http://' . env('LASALLE_JWT_AUD_CLAIM');

        $client = new Client();

        try {
            $response = $client->request('GET', $apiUrl . $path, [
                'headers'         => $headers,
                'connect_timeout' => 10,
                'query'           => ['slug' => $slug],
            ]);

            return $response;
        } catch (RequestException $e) {
            return $this->createTheErrorMessageBag($e->getResponse());
        } catch (\Exception $e) {
            return $this->createTheErrorMessageBag($e->getResponse());
        }
    }

    /**
     * Our request returned an error? Well, let's create the MessageBag instance for the view.
     *
     * @param  \GuzzleHttp\Psr7\Response  $response
     */
    public function createTheErrorMessageBag($response)
    {
        $body           = json_decode($response->getBody());
        $this->messages = new MessageBag();

        $this->messages->add('StatusCode', $response->getStatusCode());
        $this->messages->add('Error',    $body->error);
        $this->messages->add('Reason',     $body->reason);

        return;
    }
}
