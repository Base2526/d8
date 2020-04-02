<?php

/**
 * @file
 * Contains Drupal\custom_rest\Plugin\rest\resource\custom_rest.
 */

namespace Drupal\huay\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Psr\Log\LoggerInterface;

use Drupal\rest\ModifiedResourceResponse;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "custom_rest_resource",
 *   label = @Translation("Demo POST"),
 *   uri_paths = {
 *     "https://www.drupal.org/link-relations/create" = "/rest/api/post"
 *   }
 * )
 */

class PostRestResource extends ResourceBase {
  /**
   * Responds to POST requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data) {

    // 
 
    // You must to implement the logic of your REST Resource here.
    // $data = ['message' => 'Hello, this is a rest service and parameter is: '.$data->name->value];
    
    $data = ['message' => 'Hello, this is a rest service and parameter is: '.serialize($data)];
    
    
     $response = new ResourceResponse($data);
     // In order to generate fresh result every time (without clearing 
     // the cache), you need to invalidate the cache.
     $response->addCacheableDependency($data);
     return $response;
   }
}