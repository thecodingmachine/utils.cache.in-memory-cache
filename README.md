Mouf local cache service
========================

This package contains the most basic implementation of Mouf's CacheInterface. It stores cache items in an in-memory array.
It means the cache is flushed as soon as the script returns. It is very basic, but also very fast. You will usually use this mechanism with another one just behind.
To learn more about the cache interface, please see the [cache system documentation](http://mouf-php.com/packages/mouf/utils.cache.cache-interface).

Compared to Mouf's other cache implementations, the local cache system comes with an additional feature: _a chaining mechanism_.

In practice, you would put the in-memory cache in front of another cache. If your application queries several times the
same key in the same script, it will be available in-memory and the cache system will not be queried (hence a faster result).