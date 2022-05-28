<?php
namespace App\Helpers;

use App\Modules\Solr\Classes\SolrClient;
use Facades\App\Repositories\{CompanyRepository};
use Illuminate\Support\Facades\{Auth, Log, DB, Validator, Crypt};

use App\Modules\Solr\Seeders\StockSeeds;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SolrHelper
{
	const MAX_INPUT_PER_LOT = 5000;

    public function testSolr()
    {
    	// SolrClient::saveData([]);
    	try {
	    	$client = new SolrClient(config("solr.CORE_ACTIVE"));
	        if($client->hasErrors()) {
	            $resp = $client->getError();
	            Log::channel('solr')->error($resp);
	            $error = $resp;
	            throw new \Exception($resp);
	        } 
	    } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['payload' => [], 'error'=>true, 'status'=> 0], 200);
        }
    	// SolrClient::getData([]);
    }


    public  function syncStocks()
    {
        try {
            $client = new SolrClient(config("solr.CORE_ACTIVE"));
	        if($client->hasErrors()) {
	            $resp = $client->getError();
	            Log::channel('solr')->error($resp);
	            $error = $resp;
	            throw new \Exception($resp);
	        } else {
                $totalProcessedRows = 0;

                $delete = $client->deleteAll();

                // dd('delete');

                $result = CompanyRepository::getStocks();

                // $result = CompanyRepository::all();

                $this->syncData($client, $result);

                // list($totalProcessedRows, $total_beans) = $this->syncDocumentsByType($client, $result, $jobId, $totalProcessedRows);

            }
        } catch (\Exception $e) {
            Log::channel('solr')->error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            throw $e;
        }
    }

    private function syncData($client, $result)
    {
    	$seeds = [];
        $totalSeeds = [];
        $counter = 0;
        $lot = 1;

        $totalProcessedRows = 0;

        foreach ( $result as $row ) {
            $tmpSeed = new StockSeeds( $row);

            // dd($tmpSeed);
           
            $seeds[] = $tmpSeed;
            $totalSeeds[] = $tmpSeed;
            $counter++;

            if($counter == self::MAX_INPUT_PER_LOT) {
                Log::channel('solr')->info('Total Records : ' . $counter . ' in lot  ' . $lot);
                $resp = $client->saveData($seeds);
                if($resp) {
                    $totalProcessedRows = $totalProcessedRows + $counter;
                    Log::channel('solr')->info('Total Records : ' . $counter . ' in lot  ' . $lot .  ' processed');
                }  else {
                    Log::channel('solr')->error('Error for  : ' . $counter . ' in lot  ' . $lot .  ' failed');
                }
                $seeds = [];
                $counter = 0;
                $lot++;
            }
        }

        if( sizeof( $seeds ) > 0 ) {
            $resp = $client->saveData( $seeds);
            if($resp) {
                $totalProcessedRows = $totalProcessedRows + $counter;
                Log::channel('solr')->info('Total Records : ' . $counter . ' in lot  ' . $lot .  ' processed');
            }  else {
                Log::channel('solr')->error('Error for  : ' . $counter . ' in lot  ' . $lot .  ' failed');
            }
        }

        $summary = 'Records saved: '.$totalProcessedRows;
    }

    public function dummySerch($filters = [], $page = 1)
    {
        $client = new SolrClient(Config::get("solr.CORE_ACTIVE"));
        if($client->hasErrors()) {
            $resp = $client->getError();
            Log::error($resp);
            return [];
        }

        $recordsPerPage = 5;

        $start = ($page - 1) * $recordsPerPage;

        $queryParam = [
            'q' => "*:*",
            'wt' => 'json',
            'rows' => $recordsPerPage,
            'facet' => 'on',
            'facet.limit' => -1,
            // 'facet.limit' => 500,
            'facet.sort' => 'count',
            'start' => $start,
            'sort' => 'supplierName asc',
        ];

        $facetField = ['tags', 'country','city', 'companyType'];

        //$facetParam = implode('facet.field=', $facetField);

       // dd($facetParam);


        $facetParam = '';

        $facatCount = count($facetField);
        foreach ($facetField as $key =>  $facet) {
            if($key == $facatCount-1 ) {
                $facetParam .=  'facet.field='.$facet;
            } else {
                $facetParam .=  'facet.field='.$facet."&";
            }
        }

        // dd(urldecode('http://localhost:8983/solr/craft/select?fq=type_id%3A1&fq=type_id%3A23&q=*%3A*&wt=json'));

        // http://localhost:8983/solr/craft/select?fq=type_id:1&fq=type_id:23&q=*:*&wt=json

        // echo $facetParam;die;

        $solrFq = [];

        if(count($filters)) {
            foreach ($filters as $key => $value) {
                # code...
                if($value) {
                    $solrFq[$key] = explode(',',$value);
                    // $solrFq[$key] = $value;
                }
            }
        }

        // dd($solrFq);

        $fq = '';
        $facetQuery = '';

        if(count($solrFq)) {
            $i= 0;
            foreach ($solrFq as $key =>  $fields) {
                $operator = ' OR ';

                if($key == 'tags') {
                    $operator = ' AND ';
                }

                $fl = [];
                foreach ($fields as $field) {
                    $fl[]= '"'.$field.'"';
                }

                $fl = implode($operator, $fl);

                $fl = "(". $fl .")";

                if($i == count($solrFq)-1 ) {
                    // $fq .= "fq=$key:$value";
                    // $fq .= 'fq='.$key.':"'.$value.'"';
                    // $fq .= "fq=$key:$fl";
                    $fq .= "fq=".urlencode($key).":".urlencode($fl);
                    
                } else {
                    // $fq .= "fq=$key:$value&";
                    // $fq .= 'fq='.$key.':"'.$value.'"&';
                    // $fq .= 'fq='.$key.':"'.$fl.'"';
                    // $fq .= "fq=$key:$fl&";
                    $fq .= "fq=".urlencode($key).":".urlencode($fl)."&";

                }
                $i++;
            }
        }

        // $filterQuery = '"'.$filterQuery.'"~'.config("solr.PROXIMITY_DISTANCE");
            // Log::debug($filterQuery);

        // dd($fq);

        // dd($solrFq);


        $query = http_build_query($queryParam) .'&'.$facetParam;

        if($fq) {
            // $query .='&'.urlencode($fq);
            $query .='&'.$fq;
        }

       
        // echo $query;die;

        $url = config( "solr.API_URL" ) . $core . "/select?".$query;
        Log::debug($url);
        
        
        // $apiInputParams['query'] = $input;
        $client = new Client();
        // $response = $client->request('get', $url,[
        //             'auth'    => [
        //                 config("solr.SOLR_BA_USER"),
        //                 config("solr.SOLR_BA_PASS")
        //             ],"query" => $input] );

        $response = $client->request('get', $url,[
                    'auth'    => [
                        config("solr.SOLR_BA_USER"),
                        config("solr.SOLR_BA_PASS")
                    ]] );

        // $response = $client->request('get', $url);

        $response_data = json_decode($response->getBody(), true);



        // dd( $response_data);

        // $suppliers = $response_data->response->docs;

        $data['suppliers'] = $response_data['response']['docs'] ?? [];

        $data['totalRecords'] = $response_data['response']['numFound'] ?? 0;
        $data['start'] = $response_data['response']['start'] ?? 0;

        $ctypes = $response_data['facet_counts']['facet_fields']['companyType'];
        for($index = 0; $index < sizeof($ctypes); $index++) {
            $current_type = $ctypes[$index];
            // $data["company_types_items"][$current_type] = $ctypes[++$index];
            $facatValue = $ctypes[++$index] ?? 0;

            $companyTypeFilter = $solrFq['companyType'] ?? [];
            if($facatValue || in_array($current_type, $companyTypeFilter)) {
                $data["company_types_items"][$current_type] = $facatValue;
            }
        }
        
        // $tags = $response_data->facet_counts->facet_fields->tags;
        $tags = $response_data['facet_counts']['facet_fields']['tags'];
        for($index = 0; $index < sizeof($tags); $index++) {
            $current_tag = $tags[$index];
            $facatValue = $tags[++$index] ?? 0;

            $tagFilter = $solrFq['tags'] ?? [];

            if($facatValue  || in_array($current_tag, $tagFilter)) {
                // $data["tag_items"][$current_tag] = $tags[++$index];
                $data["tag_items"][$current_tag] = $facatValue;
            }

            $input = [
                 "FacetType" => 'tags',
                 "FacetName" => $current_tag
            ];
            // CraftFacetSolrRepository::updateOrCreate($input,$input);

           
        }
        
        // $countries = $response_data->facet_counts->facet_fields->country;
        $countries = $response_data['facet_counts']['facet_fields']['country'];
        for($index = 0; $index < sizeof($countries); $index++) {
            $current_country = $countries[$index];
             $facatValue = $countries[++$index] ?? 0;
            // $data["countries_items"][$current_country] = $countries[++$index];

            $countryFilter = $solrFq['country'] ?? [];

            if($facatValue || in_array($current_country, $countryFilter)) {
                $data["countries_items"][$current_country] = $facatValue;
            }

            $input = [
                 "FacetType" => 'country',
                 "FacetName" => $current_country
            ];
            // CraftFacetSolrRepository::updateOrCreate($input,$input);
        }
        
        // $cities = $response_data->facet_counts->facet_fields->city;
        $cities = $response_data['facet_counts']['facet_fields']['city'];
        for($index = 0; $index < sizeof($cities); $index++) {
            $current_city = $cities[$index];
            // $data["cities_items"][$current_city] = $cities[++$index];
            $facatValue = $cities[++$index] ?? 0;

            $cityFilter = $solrFq['city'] ?? [];


            if($facatValue || in_array($current_city, $cityFilter)) {
                $data["cities_items"][$current_city] = $facatValue;
            }
            $input = [
                 "FacetType" => 'city',
                 "FacetName" => $current_city
            ];
            // CraftFacetSolrRepository::updateOrCreate($input,$input);
        }



        return $data;

        dd($data);
        
        return collect($res);
    }

    public function getStats($filter)
    {
    	$page = 1;
        $core = config("solr.CORE_ACTIVE");

        $client = new SolrClient($core);
        if($client->hasErrors()) {
            $resp = $client->getError();
            Log::error($resp);
            return [];
        }

        $url = config( "solr.API_URL" ) . $core . "/select";
        $recordsPerPage = 5;
        $start = ($page - 1) * $recordsPerPage;

        $queryParam = [
            'q' => "*:*",
            'wt' => 'json',
            'rows' => $recordsPerPage,
            'stats' => 'on',
            'start' => $start,
            'sort' => 'id asc',
        ];

        $statsField = ['price_to_earning', 'peg_ratio','return_on_capital_employed','return_on_equity','debt_to_equity','market_capitalization'];

        $statsParam = '';

        // dd($filter);

        $fq = '';

        $filterCount = count($filter);

        $i = 0;

        foreach ($filter as $field => $value) {
        	if($i == $filterCount-1 ) {
                $fq .= "fq=".urlencode($field).":".urlencode($value);
            } else {
                $fq .= "fq=".urlencode($field).":".urlencode($value)."&";
            }
            $i++;
        }

        // echo $fq;die;

       
      
        $statsCount = count($statsField);
        foreach ($statsField as $key =>  $stats) {
            if($key == $statsCount-1 ) {
                $statsParam .=  'stats.field='.$stats;
            } else {
                $statsParam .=  'stats.field='.$stats."&";
            }
        }


       
        $query = http_build_query($queryParam) .'&'.$statsParam;

        if($fq) {
            $query .='&'.$fq;
        }

        $url = config( "solr.API_URL" ) . $core . "/select?".$query;

        // dd($url);
        // Log::debug($url);
        
        $client = new Client();
    
        $response = $client->request('get', $url );


        $res = json_decode($response->getBody(), true);

        return $res['stats']['stats_fields'] ?? [];

        dd($res['stats']['stats_fields']);
        
        // return collect($res);
    }


    
}
