<?php

namespace Drupal\huay\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "demo_resource",
 *   label = @Translation("Demo GET"),
 *   uri_paths = {
 *     "canonical" = "/rest/api/get"
 *   }
 * )
 */
class GetRestResource extends ResourceBase {

    /**
     * Responds to entity GET requests.
     * @return \Drupal\rest\ResourceResponse
     */
    public function get() {
        $response = ['message' => 'Hello, this is a rest service'];
        return new ResourceResponse($response);
    }
}