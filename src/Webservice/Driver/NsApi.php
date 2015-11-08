<?php

namespace Qarox\NsApi\Webservice\Driver;

use Cake\Network\Http\Client;
use Muffin\Webservice\AbstractDriver;

class NsApi extends AbstractDriver {
	
   	public function initialize() {
        $this->client(new Client([
            'host' => 'webservices.ns.nl',
            'scheme' => 'https',
            'auth' => [
	            'username' => $this->config('username'),
	            'password' => $this->config('password') 
            ],
            'headers' => ['Accept-Encoding' => 'gzip']
        ]));
    }
}
