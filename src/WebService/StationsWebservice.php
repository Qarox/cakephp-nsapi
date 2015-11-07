<?php

namespace Qarox\NsApi\Webservice;

use Cake\Network\Http\Client;
use Muffin\Webservice\Query;
use Muffin\Webservice\Webservice\Webservice;
use Muffin\Webservice\ResultSet;
use Cake\Utility\Xml;
class StationsWebservice extends Webservice
{

    /**
     * {@inheritDoc}
     */
    protected function _executeReadQuery(Query $query, array $options = [])
    {
        $response = $this->driver()->client()->get('/ns-api-stations-v2');
	
        if (!$response->isOk()) {
            return false;
        }
        
        $xmlData = $response->xml;
		$stations = [];
		
		foreach ($xmlData->Station as $station) {
			$synonyms = [];
			if (!empty($station->Synoniemen->Synoniem)) {
				foreach ($station->Synoniemen->Synoniem as $synonym)
					$synonyms[] = (string)$synonym;
			}
			
			
			$stations[] = [
				'code' => !empty($station->Code)?(string)$station->Code:null,
				'type' => !empty($station->Type)?(string)$station->Type:null,
				'name_short' => !empty($station->Namen->Kort)?(string)$station->Namen->Kort:null,
				'name_middle' => !empty($station->Namen->Middel)?(string)$station->Namen->Middel:null,
				'name_long' => !empty($station->Namen->Lang)?(string)$station->Namen->Lang:null,
				'synonyms' => $synonyms,
				'country' => !empty($station->Land)?(string)$station->Land:null,
				'UICCode' => !empty($station->UICCode)?(int)$station->UICCode:null,
				'latitude' => !empty($station->Lat)?(float)$station->Lat:null,
				'longitude' => !empty($station->Lon)?(float)$station->Lon:null,
			];
		}
        return new ResultSet($this->_transformResults($stations, $options['resourceClass']));
    }
}