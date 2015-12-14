<?php namespace Staff\Session;

use Illuminate\Support\ServiceProvider;

class StaffSessionServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupDefaultDriver();

        $this->registerSessionManager();

        $this->registerSessionDriver();
    }

    /**
     * Setup the default session driver for the application.
     *
     * @return void
     */
    protected function setupDefaultDriver()
    {
        if ($this->app->runningInConsole())
        {
            $this->app['config']['staff_session.driver'] = 'array';
        }
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->app->bindShared('staff_session', function($app)
        {
            return new StaffSessionManager($app);
        });
    }

    /**
     * Register the session driver instance.
     *
     * @return void
     */
    protected function registerSessionDriver()
    {
        $this->app->bindShared('staff_session.store', function($app)
        {
            // First, we will create the session manager which is responsible for the
            // creation of the various session drivers when they are needed by the
            // application instance, and will resolve them on a lazy load basis.
            $manager = $app['staff_session'];

            return $manager->driver();
        });
    }

}
