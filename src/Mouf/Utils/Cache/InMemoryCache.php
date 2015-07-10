<?php
namespace Mouf\Utils\Cache;

use Mouf\Validator\MoufValidatorInterface;
use Mouf\Validator\MoufValidatorResult;

/**
 * This package contains a cache mechanism that relies on APC.
 * 
 * @author David Negrier
 */
class InMemoryCache implements CacheInterface {
	
	
	/**
	 * The local cache can be put in front of another cache service.
	 * If the data is available in the cache, it is returned from the cache.
	 * If it is not available, it will be queried from the other cache.
	 * 
	 * @var CacheInterface
	 */
	public $chainWith;
	
	private $cache = array();
	
	/**
	 * Returns the cached value for the key passed in parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		} elseif ($this->chainWith != null) {
			$item = $this->chainWith->get($key);
			if ($item !== null) {
				$this->cache[$key] = $item;
				return $item;
			}
		}
		
		return null;
	}
	
	/**
	 * Sets the value in the cache.
	 * Note: $timeToLive is completely ignored in the LocalCache implementation,
	 * since the script is expected to be short lived.
	 *
	 * @param string $key The key of the value to store
	 * @param mixed $value The value to store
	 * @param float $timeToLive The time to live of the cache, in seconds.
	 */
	public function set($key, $value, $timeToLive = null) {
		$this->cache[$key] = $value;
		if ($this->chainWith != null) {
			$this->chainWith->set($key, $value, $timeToLive);
		}
	}
	
	/**
	 * Removes the object whose key is $key from the cache.
	 *
	 * @param string $key The key of the object
	 */
	public function purge($key) {
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}
		if ($this->chainWith != null) {
			$this->chainWith->purge($key);
		}
	}
	
	/**
	 * Removes all the objects from the cache.
	 *
	 */
	public function purgeAll() {
		$this->cache = array();
		if ($this->chainWith != null) {
			$this->chainWith->purgeAll();
		}
	}
}
?>