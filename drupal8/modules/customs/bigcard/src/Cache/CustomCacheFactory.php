<?php
 
namespace Drupal\bigcard\Cache;
 
use Drupal\Core\Cache\CacheFactory;
 
class CustomCacheFactory extends CacheFactory {
 
  public function get($bin) {
    $service_name = 'cache.backend.database.bigcard';
    return $this->container->get($service_name)->get($bin);
  }
}