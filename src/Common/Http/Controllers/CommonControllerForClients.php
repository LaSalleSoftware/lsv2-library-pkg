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

        $request = request();
        $page    = $request->input('page');
        if (isset($page)) {
            $query = [
                'slug'                          => $slug,
                'itemsDisplayedOnPaginatedPage' => config('lasallesoftware-frontendapp.lasalle_pagination_number_of_items_displayed_per_page'),
                'page'                          => $page,
            ];
        } else {
            $query = ['slug' => $slug];
        }

        try {
            $response = $client->request('GET', $apiUrl . $path, [
                'headers'         => $headers,
                'connect_timeout' => 10,
                'query'           => $query,
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
            'blogrssfeed'            => '/api/v1/blogrssfeed',
            'allblogposts'           => '/api/v1/allblogposts',
            'allcategoryblogposts'   => '/api/v1/allcategoryblogposts',
            'alltagblogposts'        => '/api/v1/alltagblogposts',
            'allauthorblogposts'     => '/api/v1/allauthorblogposts',
            'singleblogpost'         => '/api/v1/singleblogpost',
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

    /**
     * Get the featured image.
     *
     * @param  string  $featuredImage        The featured image returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImage($featuredImage)
    {
        if ($featuredImage == "none") {
            return config('lasallesoftware-frontendapp.lasalle_featured_image_default_image');
        }

        return $featuredImage;
    }

    /**
     * Get the featured image type.
     *
     * @param  string  $featuredImageType        The featured image type returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImageType($featuredImageType)
    {
        if ($featuredImageType == "none") {
            return config('lasallesoftware-frontendapp.lasalle_featured_image_default_type');
        }

        return $featuredImageType;
    }

    /**
     * Get the social media featured image.
     *
     * @param  string  $featuredImageSocialMediaMetaTag        The social media featured image returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImageSocialMediaMetaTag($featuredImageSocialMediaMetaTag)
    {
        if ($featuredImageSocialMediaMetaTag == "none") {
            return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_default_image');
        }

        return $featuredImageSocialMediaMetaTag;
    }

    /**
     * Display the error page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayErrorView()
    {
        return view(config('lasallesoftware-frontendapp.lasalle_path_to_front_end_view_path') . '.errors.main', [
            'status_code'         => $this->messages->first('StatusCode'),
            'error'               => $this->messages->first('Error'),
            'reason'              => $this->messages->first('Reason'),
            'featured_image'      => $this->getFeaturedImage('none'),
            'featured_image_type' => $this->getFeaturedImageType('none'),
            'featured_image_social_media_meta_tag' => $this->getFeaturedImageSocialMediaMetaTag('none'),
            'copyright'           => env('LASALLE_COPYRIGHT_IN_FOOTER'),
        ]);
    }

    /**
     * Format date from a date string.
     *
     * @param  string  $date     2019-09-29T04:00:00.000000Z
     * @return string
     */
    public function formatDate($date)
    {
        return date(config('lasallesoftware-frontendapp.lasalle_date_format'),strtotime($date));
    }

    /**
     * Format date from a date string specifically for the HTML time tag.
     *
     * https://www.w3schools.com/tags/tag_time.asp
     *
     * @param  string  $date     2019-09-29T04:00:00.000000Z
     * @return string
     */
    public function formatDateForHTMLTimeTag($date)
    {
        return date('Y-m-d',strtotime($date));
    }

    /**
     * Put together the full front-end URL with the 'page' query parameter so we can use it in our links.
     *
     * @param  string       $url             The pagination string sent by the back-end, generated by Laravel's
     *                                       pagination instance methods (previousPageUrl() and nextPageUrl())
     *                                       https://laravel.com/docs/6.x/pagination#paginator-instance-methods
     * @param  stromg       $frontEndRoute   Maybe "/blog/all" or "/category/buffalobills" or something-like-that.
     * @return string|null
     */
    public function getPageURLForPagination($url = null, $frontEndRoute)
    {
        if ((is_null($url)) || ($url == '')) {
            return null;
        }

        return env('APP_URL') . '/' . $frontEndRoute . '?page=' . $this->getPageQueryParameterFromUrlString($url) ;
    }

    /**
     * Return the value of the page query parameter in a URL.
     *
     * If the value is null, return null.
     *
     * Raison d'etre: to grab the page number in the Laravel pagination links (https://laravel.com/docs/6.x/pagination)
     *
     * @param  string     $url    Eg: 'http://hackintosh.lsv2-adminbackend-app.com:8888/api/v1/allarticlesblog?page=1'
     * @return mixed|null
     */
    public function getPageQueryParameterFromUrlString($url)
    {
        $queryParameterString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryParameterString, $output);
        return isset($output['page']) ? $output['page'] : null;
    }

    /**
     * Get the value of the twitter:site social media meta tag.
     *
     * https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
     *
     * @return string
     */
    public function getSocialMediaMetaTagSite()
    {
        return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_site');
    }

    /**
     * Get the value of the twitter:creator social media meta tag.
     *
     * https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
     *
     * @return string
     */
    public function getSocialMediaMetaTagCreator()
    {
        if (config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_creator') == '') {
            return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_site');
        }

        return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_creator');
    }

    /**
     * Transform posts from the incoming API request.
     *
     * Yes, I am fully aware of that thing called https://laravel.com/docs/6.x/eloquent-resources. Need I really have
     * to play with every toy in the toy box?
     *
     * @param  array  $posts
     * @return array
     */
    public function getTransformedPosts($posts)
    {
        $transformedPosts = [];

        foreach ($posts as $post) {

            $transformedPost = [
                'title'                          => $post->title,
                'slug'                           => $post->slug,
                'author'                         => $post->author,
                'excerpt'                        => $post->excerpt,
                'featured_image'                 => $this->getFeaturedImage($post->featured_image),
                'featured_image_type'            => $this->getFeaturedImageType($post->featured_image_type),
                'publish_on'                     => $this->formatDate($post->publish_on),
                'datetime'                       => $this->formatDateForHTMLTimeTag($post->publish_on),
            ];

            $transformedPosts[] = $transformedPost;
        }

        return $transformedPosts;
    }
}
