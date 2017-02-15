<?php namespace Nikapps\BazaarApiLaravel\Console;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class BazaarApiClearCacheCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bazaar:clear-cache';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will clear access token from cache';

    /**
     * @var ConfigRepository
     */
    protected $config;


    /**
     * Create a new command instance.
     *
     * @param ConfigRepository $config
     * @return \Nikapps\BazaarApiLaravel\Console\BazaarApiClearCacheCommand
     */
    public function __construct(ConfigRepository $config)
    {

        $this->config = $config;

        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $cacheDriver = $this->option('driver');
        $cacheName = $this->option('name');

        if (\Cache::driver($cacheDriver)->has($cacheName)) {
            $this->comment('Value: ' . \Cache::driver($cacheDriver)->get($cacheName));
            \Cache::driver($cacheDriver)->forget($cacheName);

            $this->info("$cacheName has been successfully removed from cache $cacheDriver");
        } else {
            $this->error("$cacheName is already cleared from cache $cacheDriver");
        }

        $this->info('Done!');
    }

    /**
     * command options
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                "driver",
                "d",
                InputOption::VALUE_OPTIONAL,
                "cache driver ",
                $this->config->get('bazaar-api-laravel::cache.cache_driver')
            ],
            [
                "name",
                "cn",
                InputOption::VALUE_OPTIONAL,
                "key of cache ",
                $this->config->get('bazaar-api-laravel::cache.cache_name')
            ],


        ];
    }
}
