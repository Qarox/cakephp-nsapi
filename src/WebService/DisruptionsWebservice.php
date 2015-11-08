<?php

namespace Qarox\NsApi\Webservice;

use Cake\Network\Http\Client;
use Muffin\Webservice\Query;
use Muffin\Webservice\Webservice\Webservice;
use Muffin\Webservice\ResultSet;
use Cake\Utility\Xml;
class DisruptionsWebservice extends Webservice
{

    /**
     * {@inheritDoc}
     */
    protected function _executeReadQuery(Query $query, array $options = [])
    {
	    $vars = [];
	    foreach ($query->where() as $k => $v) {
			if ($k == 'actual' || $k == 'unplanned')
				$vars[$k] = is_string($v)?$v:($v?'true':'false');
			else
				$vars[$k] = $v;
	    }
	    
        $response = $this->driver()->client()->get('/ns-api-storingen', $vars);
	
        if (!$response->isOk()) {
            return false;
        }
        $xmlData = $response->xml;
        
        $disruptions = [];
        
        $keys = ['Ongepland', 'Gepland'];
        
        foreach ($keys as $key) {

	    	foreach ($xmlData->{$key}->Storing as $disruption) {
		    	$disruptions[] = [
			    	'id' => !empty($disruption->id)?(string)$disruption->id:null,
			    	'planned' => ($key == 'Gepland'),
			    	'route' => !empty($disruption->Traject)?(string)$disruption->Traject:null,
			    	'reason' => !empty($disruption->Oorzaak)?(string)$disruption->Oorzaak:null,
			    	'advice' => !empty($disruption->Advies)?(string)$disruption->Advies:null,
			    	'message' => !empty($disruption->Bericht)?(string)$disruption->Bericht:null,
			    	'delay' => !empty($disruption->Vertraging)?(string)$disruption->Vertraging:null,
			    	'period' => !empty($disruption->Periode)?(string)$disruption->Periode:null,
		    	];
	    	}    
        }
        
        return new ResultSet($this->_transformResults($disruptions, $options['resourceClass']));
    }
}