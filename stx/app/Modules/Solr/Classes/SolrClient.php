<?php
    namespace App\Modules\Solr\Classes;

    use Solarium\Client;
    use Solarium\Core\Client\Adapter\Curl;

    use Solarium\Plugin\BufferedAdd\Event\Events;
    use Solarium\Plugin\BufferedAdd\Event\PreFlush as PreFlushEvent;

    use Symfony\Component\EventDispatcher\EventDispatcher;

    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Log;

    use Facades\App\Repositories\{CompanyRepository};

    
    class SolrClient
    {
        private $core_name = null;
        private $client = null;
        private $query = null;
        private $error = null;
        private $errorCode = null;
        private $appEnv = null;
        private $devMode = true;


        public function __construct($core)
        {
            // dd($core);
            $this->error = null;
            $this->devMode = ( env( 'APP_ENV', 'local' ) == 'local' );

            if( empty( Config::get("solr.SOLR_END_POINTS_TEMPLATE", null ) ) ) {
                $this->error = "Config Missing";
            } else if( empty( $core ) ) {
                $this->error = "Invalid Core Presented. Please check.";
            } else {
                $this->core_name = $core;
                try {
                    $this->solrClientInstance( $core );
                } catch( \Throwable $t ) {
                    $this->handleException  (
                        Config::get( "solr.ERROR_1" ), 99001,
                        "Failed to connect to Solr: ".$core."<br />Exception Details: ".$t->getMessage()." :: ".$t->getCode()
                    );
                } catch( \Exception $ex ) {
                    $this->handleException  (
                        Config::get( "solr.ERROR_1" ),  99001,
                        "Failed to connect to Solr: ".$core."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                    );
                }
                try {
                    $ping = $this->client->createPing();
                    $this->client->ping( $ping );
                } catch( \Throwable $t ) {
                    $this->handleException  (
                        Config::get("solr.ERROR_404"),  404,
                        "No Such Core: ".$core."<br />Exception Details: ".$t->getMessage()." :: ".$t->getCode()
                    );
                } catch( \Solarium\Exception\HttpException $hex ) {
                    $this->handleException  (
                        Config::get("solr.ERROR_404"),  404,
                        "No Such Core: ".$core."<br />Exception Details: ".$hex->getMessage()." :: ".$hex->getCode()
                    );
                } catch( \Exception $ex ) {
                    $this->handleException  (
                        Config::get( "solr.ERROR_404" ),  404,
                        "No Such Core: ".$core."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                    );
                }
            }
            
        }

        public function hasErrors()
        {
            return !empty( $this->error );
        }

        public function getError()
        {
            return $this->error;
        }

       
        public function getErrorCode()
        {
            return $this->errorCode;
        }

        private function handleException($error, $error_code, $publish_message)
        {
            $this->error = $error;
            $this->errorCode = $error_code;
            Log::error("ERROR CODE: ".$error_code." ERROR: ".$error. PHP_EOL ."PUBLISHED MSG: ".$publish_message. PHP_EOL . PHP_EOL);
            
        }
        
        public function saveDataTest($data_set)
        {
            try {

                $data_set = CompanyRepository::all();

                // dd($result);

                // dd($this->client);
                $query = $this->client->createUpdate();
                $docs = [];

                // $data_set = [
                //     ['id' =>  123, "name" => 'testdoc-1', "price" => 787.90],
                //     ['id' =>  124, "name" => 'testdoc-2', "price" => 32323.90],
                //     ['id' =>  125, "name" => 'testdoc-3', "price" => 11.67],
                // ];

                // dd($data_set);

                foreach($data_set as $index => $row) {       
                    // dd($row);           
                    // $docs[$index] = $doc->seed($query->createDocument());
                    $obj = $query->createDocument();
                    $obj->id = $row['id'];
                    $obj->name = $row['company_name'];
                    $obj->price = $row['current_price'];
                    $docs[$index] = $obj;
                }

                // dd($docs);

                // $doc1 = $query->createDocument();
                // $doc1->id = 123;
                // $doc1->name = 'testdoc-1';
                // $doc1->price = 364.89;

                // // and a second one
                // $doc2 = $query->createDocument();
                // $doc2->id = 124;
                // $doc2->name = 'testdoc-2';
                // $doc2->price = 340.90;

                // add the documents and a commit command to the update query
                // $query->addDocuments(array($doc1, $doc2));
                // $query->addDocuments(array($doc1, $doc2));
                $query->addDocuments($docs);
                $query->addCommit();

                // this executes the query and returns the result
                $result = $this->client->update($query);
                echo '<b>Update query executed</b><br/>';
                echo 'Query status: ' . $result->getStatus(). '<br/>';
                echo 'Query time: ' . $result->getQueryTime();
                // return true;
            } catch(\Exception $e) {
                // $this->handleException  (
                //         Config::get("solr.ERROR_3"),  99003,
                //         "Posting report data failed for : ".$this->core_name."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                // );

                Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
                return false;
            }
        }

        public function saveData($data_set)
        {
            try {
                $query = $this->client->createUpdate();
                $docs = [];

                foreach($data_set as $index => $row) {       
                    // dd($row->seed($query->createDocument()));           
                    $docs[$index] = $row->seed($query->createDocument());
                }

                // dd($docs);

               
                $query->addDocuments($docs);
                $query->addCommit();

                // this executes the query and returns the result
                $result = $this->client->update($query);
                echo '<b>Update query executed</b><br/>';
                echo 'Query status: ' . $result->getStatus(). '<br/>';
                echo 'Query time: ' . $result->getQueryTime();
                // return true;
            } catch(\Exception $e) {
                // $this->handleException  (
                //         Config::get("solr.ERROR_3"),  99003,
                //         "Posting report data failed for : ".$this->core_name."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                // );

                Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
                return false;
            }
        }


        private function solrClientInstance($core)
        {
            try {
                $adapter = new Curl(); 
                $eventDispatcher = new EventDispatcher();

                $solrConfig = config('solr.SOLR_END_POINTS_TEMPLATE');
                $solrConfig["endpoint"]["CoreEndPoint"]["core"] = $core;

                $client = new Client($adapter, $eventDispatcher, $solrConfig);

                $this->client = $client;
            } catch (\Exception $e) {
                Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            }
        }
        public function delete( $id = null, $attr_type = null, $attr_value = null )
        {
            try {
                $this->query = $this->client->createUpdate();

                if($id) {
                    $del_filters = "id:".$id;
                }

                if($attr_type && $attr_value) {
                    $del_filters = "$attr_type:".$attr_value;
                }
                //dd($del_filters);
                $this->query->addDeleteQuery($del_filters);
                $this->query->addCommit();

                // this executes the query and returns the result
                $result = $this->client->update($this->query);
                return true;
            } catch(\Exception $ex) {
                $this->handleException  (
                        Config::get("solr.ERROR_3")."::delete::".$id,  99003,
                        "Delete Query Failure: ".$id."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                );
                return false;
            }
            return false;
        }

        public function deleteAll()
        {
            try {
                $this->query = $this->client->createUpdate();
                $del_filters = "*:*";
                // $client->deleteByQuery("*:*");

                $this->query->addDeleteQuery($del_filters);
                $this->query->addCommit();

                // this executes the query and returns the result
                $result = $this->client->update($this->query);
                return true;
            } catch(\Exception $ex) {
                $this->handleException  (
                        Config::get("solr.ERROR_3")."::delete::".$id,  99003,
                        "Delete Query Failure: ".$id."<br />Exception Details: ".$ex->getMessage()." :: ".$ex->getCode()
                );
                return false;
            }
            return false;
        }
    }
