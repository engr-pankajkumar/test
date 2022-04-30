<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Helpers\{AppHelper, SolrHelper};
use Illuminate\Support\Facades\{Auth, Log, DB, Validator, Crypt};
use Facades\App\Repositories\{CompanyRepository};



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(SectorRepository::all());
        // SolrHelper::testSolr();
        // SolrHelper::syncStocks();

        // SolrHelper::getStats();

        
        // echo 'dssd';die;
        return view('stocks');
        // return view('home');
    }


    public function syncSolr()
    {
        // SolrHelper::syncStocks();
        $filter = [
            'sector_id' => 43,
            'industry_id' => 5,
        ];
        SolrHelper::getStats($filter);

    }
    public function getSectors(Request $request)
    {
        try {
            if($request->ajax()) {
                $input = $request->all();
                $rules = [
                    // 'supplier_type'   => 'required',
                    // 'week'   => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $validationErrors = $validator->errors();
                    $errors = [];

                    foreach ($rules as $key => $value) {
                        $firstError = $validationErrors->first($key);
                        $errors[$key] = $firstError;
                    }

                    $payload['errors'] =  $errors;
                    return response()->json(['payload' => $payload, 'error'=>'validations', 'status'=> 0], 200);
                } else {
                   
                    $sectors = AppHelper::getSectors();
                    $status = 1;
                    $error = false;
                    $payload = [];

                    $payload['sectors'] = $sectors;
                }

                return response()->json(['payload' => $payload, 'error' => $error, 'status' => $status], 200);
            } else {
                return response()->json(['payload' => [], 'error' => 'invalid request', 'status' => 0], 200);
            }
            // echo '<pre>'; print_r($dataCsCd->toArray()); die;
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['payload' => [], 'error'=>true, 'status'=> 0], 200);
        }
    }

    public function getIndustries(Request $request)
    {
        try {
            if($request->ajax()) {
                $input = $request->all();
                $rules = [
                    'sector'   => 'required',
                    // 'week'   => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $validationErrors = $validator->errors();
                    $errors = [];

                    foreach ($rules as $key => $value) {
                        $firstError = $validationErrors->first($key);
                        $errors[$key] = $firstError;
                    }

                    $payload['errors'] =  $errors;
                    return response()->json(['payload' => $payload, 'error'=>'validations', 'status'=> 0], 200);
                } else {
                    $sectorId = $request->input('sector');
                    $sectors = AppHelper::getIndustries($sectorId);
                    $status = 1;
                    $error = false;
                    $payload = [];

                    $payload['industries'] = $sectors;
                }

                return response()->json(['payload' => $payload, 'error' => $error, 'status' => $status], 200);
            } else {
                return response()->json(['payload' => [], 'error' => 'invalid request', 'status' => 0], 200);
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['payload' => [], 'error'=>true, 'status'=> 0], 200);
        }
    }

    public function getCompanies(Request $request)
    {
        try {
            if($request->ajax()) {
                $input = $request->all();
                $rules = [
                    'sector'   => 'required',
                    'industry'  => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $validationErrors = $validator->errors();
                    $errors = [];

                    foreach ($rules as $key => $value) {
                        $firstError = $validationErrors->first($key);
                        $errors[$key] = $firstError;
                    }

                    $payload['errors'] =  $errors;
                    return response()->json(['payload' => $payload, 'error'=>'validations', 'status'=> 0], 200);
                } else {
                    $sector = $request->input('sector');
                    $industry = $request->input('industry');

                    $where = [
                        'sector'   => $sector,
                        'industry'  => $industry,
                    ];

                    $filter = [
                        'sector_id' => $sector,
                        'industry_id' => $industry,
                    ];


                    // $companies = CompanyRepository::findBy($where);

                    $companies = CompanyRepository::getStocks($where);
                    $status = 1;
                    $error = false;
                    $payload = [];

                    $view  = \View::make('partials.companies', compact('companies') );

                    $viewHtml = $view->render();
                    // return $viewHtml;

                    // $payload['news'] = SupplierNews::getSupplierNewsForFrontEnd($clientId)->get();
                    $payload['scrips'] = $viewHtml;
                    // $payload['stats'] = SolrHelper::getStats($filter);
                    
                }

                return response()->json(['payload' => $payload, 'error' => $error, 'status' => $status], 200);
            } else {
                return response()->json(['payload' => [], 'error' => 'invalid request', 'status' => 0], 200);
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['payload' => [], 'error'=>true, 'status'=> 0], 200);
        }
    }
}
