<?php

$http_prefix = "http";
// $host = "localhost";
// $host = "tscdelphp03";
// $host = '54.154.105.141';
$host = env('SOLR_HOST', 'localhost');
$port = env('SOLR_PORT', 8983);
$solr_base = "/solr/";
$user = env('SOLR_USERNAME', 'tscAdmin');
$password =  env('SOLR_PASSWORD', 'SolrRocks');

$base_url = $http_prefix."://".$host.":".$port.$solr_base;

return [
     "API_URL" => $base_url,
     "SOLR_BA_USER" => $user,
     "SOLR_BA_PASS" => $password,
     "SOLR_END_POINTS_TEMPLATE" => [  
          "endpoint" => [ 
               "CoreEndPoint" => [ 
                    "host" => $host, 
                    "port" => $port, 
                    // "core" => "NONE", 
                    // "core" => "craft_supplier", 
                    // "core" => "test", 
                    'path' => '/',
               ],
          ],
     ],
     'CORE_ACTIVE' => 'test',
     "ERROR_1" => "Solr not running.",
     "ERROR_2" => "Invalid file presented.",
     "ERROR_3" => "Query failed to execute.",
     "ERROR_4" => "Document Update failed.",
     "ERROR_404" => "Provided core is not available.",
     "ERROR_500" => "Solr processing failed with error.",
];
