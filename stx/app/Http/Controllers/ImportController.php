<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Repositories\UserRepository;
use Facades\App\Repositories\SectorRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockImport;
use Facades\App\Helpers\AppHelper;
use Illuminate\Support\Facades\{Auth, Log, DB, Validator, Crypt};



class ImportController extends Controller
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
        $sectors = SectorRepository::findBy(['status'=> 1],'get',  ['id', 'sector_name'])->pluck('sector_name', 'id');

        // dd($sectors);
        // echo 'dssd';die;
        return view('upload',compact('sectors'));
    }

    public function upload(Request $request)
    {
        // dd($request->all());    
        $sector = $request->input('sector');

        // dd($sector);

        $data['sector'] = $sector;
        Excel::import(new StockImport($data), $request->file('file'));
        // return redirect('/import')->with('status', 'File imported successfully.');
        return back()->with('status', 'File imported successfully.');
    }
}
