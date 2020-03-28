<?php
 
namespace Drupal\huay\Cache;
 
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
 
/**
 * Class MyCache.
 *
 * This class combines a static cache and an 'active' cache, loaded from a the
 * default Drupal cache location.
 *
 * @package Drupal\my_module\Cache
 * 
 * ref : https://www.hashbangcode.com/article/drupal-8-custom-cache-bins
 */
class MyCache {
 
  /**
   * The default cache bin.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;
 
  /**
   * MyCache constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The default cache bin.
   */
  public function __construct(CacheBackendInterface $cache) {
    $this->cache = $cache;
  }
 
  /**
   * Sets a cache with a specific id, data and type.
   *
   * @param string $id
   *   The cache id.
   * @param mixed $data
   *   The data to be cached.
   * @param string $type
   *   The type of data being cached. This is used to set up the cache tags.
   */
  public function setCache($id, $data, $type): void {
    $cid = 'huay:' . $type . ':' . $id;
 
    $tags = [
      'huay:' . $type . ':' . $id,
      'huay:' . $type,
      'huay',
    ];
 
    $tags = Cache::mergeTags($tags, [$cid]);
 
    // Set the database cache.
    $this->cache->set($cid, $data, (new \DateTime('+1 day'))->getTimestamp(), $tags);
 
    // Set the static cache.
    $staticCache = &drupal_static(__FUNCTION__ . $cid, NULL);
    $staticCache = $data;
  }
 
  /**
   * Get a specific cache.
   *
   * @param string $id
   *   The cache ID.
   * @param string $type
   *   The cache type.
   *
   * @return mixed
   *   The cache or false if the cache was not found.
   */
  public function getCache($id, $type) {
    $cid = 'huay:' . $type . ':' . $id;
 
    $staticCache = &drupal_static(__FUNCTION__ . $cid, NULL);
 
    if ($staticCache) {
      // If the static cache exists, then return it.
      return $staticCache;
    }
 
    // Get the cache out of the database and return the data component.
    $result = $this->cache->get($cid);
    return $result->data ?? NULL;
  }
}