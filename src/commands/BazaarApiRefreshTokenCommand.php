<?php namespace Nikapps\BazaarApiLaravel\Commands;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\Command;


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


    private $generateCodeUri = 'https://pardakht.cafebazaar.ir/auth/authorize/?response_type=code&access_type=offline&redirect_uri=%s&client_id=%s';

    /**
     * Create a new command instance.
     *
     * @return \Nikapps\BazaarApiLaravel\Commands\BazaarApiRefreshTokenCommand
     */
    public function __construct() {
        parent::__construct();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
//        $code = $this->option('code');
//        $redirectUri = $this->option('redirect-uri');
//
//            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
//
//
//        try {
//            /** @var FutureResponse $response */
//            $response = $client->post($authorizationRequest->getUri(), [
//                'body'   => $authorizationRequest->getPostData(),
//                'verify' => Config::get('bazaar-api-laravel::api.verify_ssl')
//            ]);
//
//            $result = $response->json();
//
//            $this->info("Refresh Token: " . $result['refresh_token']);
//            $this->comment('Save refresh token in your config file');
//
//            //rendering result as a table
//            $rows = [$result];
//            $headers = array_keys($result);
//
//            $this->table($headers, $rows);
//
//
//        } catch (ClientException $e) {
//            $this->error("Error: " . $e->getMessage());
//            $this->info("Request Url: " . $e->getRequest()->getUrl());
//
//            if ($e->getResponse()->getStatusCode() == 401) {
//                $this->comment('You should generate new code from this url: ');
//
//                $this->line(sprintf($this->generateCodeUri,
//                    $authorizationRequest->getRedirectUri(),
//                    $authorizationRequest->getClientId()
//                ));
//            }
//        }

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
                Config::get('bazaar-api-laravel::credentials.redirect_uri')
            ]

        ];
    }

} 