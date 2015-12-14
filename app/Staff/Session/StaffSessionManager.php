<?php namespace Staff\Session;

use Illuminate\Session\SessionManager;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Session\CacheBasedSessionHandler;
use Illuminate\Session\Store;


class StaffSessionManager extends SessionManager
{

    /**
     * Create an instance of the file session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createNativeDriver()
    {
        $path = $this->app['config']['staff_session.files'];

        return $this->buildSession(new FileSessionHandler($this->app['files'], $path));
    }

    /**
     * Create the cache based session handler instance.
     *
     * @param  string  $driver
     * @return \Illuminate\Session\CacheBasedSessionHandler
     */
    protected function createCacheHandler($driver)
    {
        $minutes = $this->app['config']['staff_session.lifetime'];

        return new CacheBasedSessionHandler($this->app['cache']->driver($driver), $minutes);
    }

    /**
     * Build the session instance.
     *
     * @param  \SessionHandlerInterface  $handler
     * @return \Illuminate\Session\Store
     */
    protected function buildSession($handler)
    {
        return new Store($this->app['config']['staff_session.cookie'], $handler);
    }

    /**
     * Get the session configuration.
     *
     * @return array
     */
    public function getSessionConfig()
    {
        return $this->app['config']['staff_session'];
    }

    /**
     * Get the default session driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['staff_session.driver'];
    }

    /**
     * Set the default session driver name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['staff_session.driver'] = $name;
    }

}
