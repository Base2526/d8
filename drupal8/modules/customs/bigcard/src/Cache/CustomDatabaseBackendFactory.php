<?php
 
namespace Drupal\bigcard\Cache;
 
use Drupal\Core\Cache\DatabaseBackendFactory;
use Drupal\bigcard\Cache\CacheDatabaseBackend;
 
class CustomDatabaseBackendFactory extends DatabaseBackendFactory {
 
  /**
   * {@inheritDoc}
   */
  public function get($bin) {
    $max_rows = $this->getMaxRowsForBin($bin);
    return new CacheDatabaseBackend($this->connection, $this->checksumProvider, $bin, $max_rows);
  }
 
  /**
   * {@inheritDoc}
   */
  protected function getMaxRowsForBin($bin) {
    return CacheDatabaseBackend::DEFAULT_MAX_ROWS;
  }
}