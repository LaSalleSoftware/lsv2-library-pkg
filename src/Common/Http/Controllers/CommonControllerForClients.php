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

        $apiUrl = $this->getApiURL();

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
        $this->messages->add('Error',      $body->error);
        $this->messages->add('Reason',     $body->reason);

        return;
    }

    /**
     * Define a list of back-end (admin) endpoints.
     *
     * I am thinking that it would be handy to have an array with the API paths. Maybe if I change to "v2", then
     * going to one method and changing the paths in one fell swoop instead of searching the codebase for the paths
     * would be handy. Maybe putting this method in this controller class is not the right place, but not really sure
     * where else at this point.
     *
     * @return array
     */
    public function apiEndpointsList()
    {
        return [
            'singlearticleblog' => '/api/v1/singlearticleblog',
        ];
    }

    /**
     * Get the API (admin) path for a specific frontend controller class.
     *
     * @param  string  $frontendControllerClass       What controller classs
     * @return string
     */
    public function getApiPath($frontendControllerClass)
    {
        $apiEndpointList = $this->apiEndpointsList();
        return $apiEndpointList[$frontendControllerClass];
    }

    /**
     * Get the URL to the admin API.
     *
     * @return string
     */
    private function getApiURL()
    {
        return env('LASALLE_ADMIN_API_URL');
    }



    private function justKeepingThisCodeSnippet()
    {
        try {
            $response = $this->sendRequestToLasalleBackend($comment, $path);

            // Here the code for successful request
            $body = json_decode($response->getBody());

            //$this->messages->add('StatusCode', $response->getStatusCode());

            //echo "<h1>" . $getUrl . "</h1>";
            echo "<h1>" . $response->getStatusCode() . "</h1>";
            echo "message = " . $body->message;

            //$this->viewPost($body->post, $body->tags);
            $this->viewPost($body->post);

            //$this->viewPostupdates($body->postupdates);

            //echo "<br><br>---- end of post! -----<br>";

            //echo "<h1>token = "  . $body->token;
            //echo "<br>domain = " . $body->domain;

        } catch (RequestException $e) {

            // BAD REQUEST
            // The server cannot or will not process the request due to something that is perceived to be a client error
            // (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
            // https://httpstatuses.com/400
            if ($e->getResponse()->getStatusCode() == '400') {
                echo "Got response 400 - Bad Request";
            }

            // UNAUTHORIZED
            // The request has not been applied because it lacks valid authentication credentials for the target resource.
            // https://httpstatuses.com/401
            if ($e->getResponse()->getStatusCode() == '401') {
                echo "Got response 401 - Unauthorized";
            }

            // FORBIDDEN
            // The server understood the request but refuses to authorize it.
            // https://httpstatuses.com/403
            if ($e->getResponse()->getStatusCode() == '403') {
                echo "Got response 404 - Forbidden";
            }

            // NOT FOUND
            // The origin server did not find a current representation for the target resource or
            // is not willing to disclose that one exists.
            // https://httpstatuses.com/404
            if ($e->getResponse()->getStatusCode() == '404') {
                echo "Got response 404 - Not Found";
            }

        } catch (\Exception $e) {

            // There was another exception.
            echo "No response was received. No status code nor any diagnostic information was given to us.";

        }
    }
}
