<?php
class Application_Model_Memcache
{
    /**
     * $_lifeTime string
     */
    private $_lifeTime = '7200';

    /**
     * $_appPath string
     */
    private $_appPath = APPLICATION_PATH;

    /**
     *
     * $_dir string
     */
    private $_dir = '/../bin/tmp/';

    /**
     *
     * $_cacheObj obj
     */
    private $_cacheObj = null;

    /**
     * Allows for setting the life time of the cache from outside
     * @param string $input
     */
    public function setLifeTime($input)
    {
        $this->_lifeTime = $input;
    }
    /**
     * Retrieves the life time of the cache
     * @param string $input
     */
    private function getLifeTime()
    {
        return $this->_lifeTime;
    }

    /**
     * Allows for setting the directory for the cache files from outside
     * @param string $input
     */
    public function setDir($input)
    {
        $this->_dir = $input;
    }
    /**
     * Retrieves the cache directory (full path)
     * @param string $input
     */
    private function getDir()
    {
        return $this->_appPath.$this->_dir;
    }
    /**
     * Allows for setting the cache object
     * @param string obj
     */
    public function setCache($input)
    {
        $this->_cacheObj = $input;
    }
    /**
     * Retrieves the cache directory (full path)
     * @param string $input
     */
    public function getCache()
    {
        return $this->_cacheObj;
    }

    /**
     * Instantiates the Zend Cache objects and stores it
     */
    public function __construct()
    {
        $frontendOptions = array(
            'lifetime' => $this->getLifeTime(), // cache lifetime of 2 hours
            'automatic_serialization' => true
        );

        $backendOptions = array(
            'cache_dir' => $this->getDir() // Directory where to put the cache files
        );

        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core',
                                    'File',
                                    $frontendOptions,
                                    $backendOptions);
        $this->setCache($cache);

    }


}

