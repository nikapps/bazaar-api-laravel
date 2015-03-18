<?php namespace Nikapps\BazaarApiLaravel\Console;

use Nikapps\BazaarApiPhp\BazaarApi;
use Nikapps\BazaarApiPhp\Configs\AccountConfig;
use Nikapps\BazaarApiPhp\Configs\ApiConfig;
use Nikapps\BazaarApiPhp\Exceptions\NetworkErrorException;
use Nikapps\BazaarApiPhp\Models\Requests\AuthorizationRequest;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;
use Illuminate\Config\Repository as ConfigRepository;


class BazaarApiRefreshTokenCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bazaar:refresh-token';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fetch refresh_token from CafeBazaar';

    /**
     * @var ConfigRepository
     */
    protected $config;

    private $generateCodeUri = 'https://pardakht.cafebazaar.ir/auth/authorize/?response_type=code&access_type=offline&redirect_uri=%s&client_id=%s';


    /**
     * Create a new command instance.
     *
     * @param ConfigRepository $config
     * @return \Nikapps\BazaarApiLaravel\Console\BazaarApiRefreshTokenCommand
     */
    public function __construct(ConfigRepository $config) {

        $this->config = $config;

        parent::__construct();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {


        $code = $this->option('code');
        $redirectUri = $this->option('redirect-uri');

        $apiConfig = $this->getApiConfig();
        $accountConfig = $this->getAccountConfig($code, $redirectUri);

        $bazaarApi = new BazaarApi();
        $bazaarApi->setApiConfig($apiConfig);
        $bazaarApi->setAccountConfig($accountConfig);

        $this->line(sprintf($this->generateCodeUri,
            $accountConfig->getRedirectUri(),
            $accountConfig->getClientId()
        ));


        try {
            $fetchedRefreshToken = $bazaarApi->fetchRefreshToken(new AuthorizationRequest());

            $json = $fetchedRefreshToken->getResponseJson();

            $headers = array_keys($json);
            $rows = [$json];

            $this->info("Refresh Token: " . $fetchedRefreshToken->getRefreshToken());
            $this->comment('Save refresh token in your config file');

            $this->table($headers, $rows);

        } catch (NetworkErrorException $e) {

            $this->error('Error: ' . $e->getClientException()->getRequest()->getUrl());
            $this->error('Response Status: ' . $e->getClientException()->getResponse()->getStatusCode());

            if($e->getClientException()->getResponse()->getStatusCode() == 401) {
                $this->comment('Your credentials is invalid or you should generate new code from this url: ');

                $this->line(sprintf($this->generateCodeUri,
                    $accountConfig->getRedirectUri(),
                    $accountConfig->getClientId()
                ));
            }

        } catch (\Exception $e){
            $this->error("Unknown Error: " . $e->getMessage());
        }


    }

    /**
     * command options
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ["code", "c", InputOption::VALUE_REQUIRED, "returned code from cafebazaar", null],
            [
                "redirect-uri",
                "r",
                InputOption::VALUE_OPTIONAL,
                "redirect-uri is defined in cafebazaar panel ",
                $this->config->get('bazaar-api-laravel::credentials.redirect_uri')
            ]

        ];
    }

    /**
     * fill api config & return
     *
     * @return ApiConfig
     */
    protected function getApiConfig(){

        $apiConfig = new ApiConfig();
        $apiConfig->setAuthorizationGrantType($this->config->get('bazaar-api-laravel::api.authorization.grant_type'));
        $apiConfig->setAuthorizationPath($this->config->get('bazaar-api-laravel::api.authorization.path'));
        $apiConfig->setBaseUrl($this->config->get('bazaar-api-laravel::api.base_url'));
        $apiConfig->setVerifySsl($this->config->get('bazaar-api-laravel::api.verify_ssl'));

        return $apiConfig;
    }

    /**
     * fill account config & return
     *
     * @param $code
     * @param $redirectUrl
     * @return \Nikapps\BazaarApiPhp\Configs\AccountConfig
     */
    protected function getAccountConfig($code, $redirectUrl){

        $accountConfig = new AccountConfig();
        $accountConfig->setClientId($this->config->get('bazaar-api-laravel::credentials.client_id'));
        $accountConfig->setClientSecret($this->config->get('bazaar-api-laravel::credentials.client_secret'));
        $accountConfig->setRedirectUri($redirectUrl);
        $accountConfig->setCode($code);

        return $accountConfig;
    }

} 