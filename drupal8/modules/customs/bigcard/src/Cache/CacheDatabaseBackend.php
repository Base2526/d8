<?php
 
namespace Drupal\bigcard\Cache;
 
use Drupal\Core\Cache\DatabaseBackend;
 
class CacheDatabaseBackend extends DatabaseBackend {
 
  /**
   * {@inheritdoc}
   *
   * We deliberately set this higher as we have a lot of data to store.
   */
  const DEFAULT_MAX_ROWS = 5000000;
 
  /**
   * {@inheritdoc}
   */
  public function deleteAll() {
    // This method is called during a normal Drupal cache clear. We absolutely
    // do not want to flush the caches so we do nothing.
  }
 
  /**
   * This method will call the original deleteAll() method.
   *
   * Calling this method will permanently delete the caches for this cache bin.
   *
   * @throws \Exception
   */
  public function deleteCustomCaches() {
    parent::deleteAll();
  }
}