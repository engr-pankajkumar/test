<?php

namespace App\Imports;

use App\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Facades\App\Repositories\CompanyRepository;
use Facades\App\Repositories\SectorRepository;
use Facades\App\Repositories\IndustryRepository;

class StockImport implements ToModel,WithHeadingRow
{
	private $data;

	public function __construct($data)
    {
        $this->data  = $data;
    }


    public function model(array $row)
    {
    	// dd($row);

    	$sector = $this->data['sector'];

    	// dd($sectorId);

    	$industry = IndustryRepository::findOrCreate(["industry_name" => $row['industry'], 'sector_id' => $sector]);

    	// dd($industry->id);
    	$input = [
    	  "company_name" => $row['name'],
		  "bse_code" => $row['bse_code'],
		  "nse_code" => $row['nse_code'],
		  "sector" => $sector,
		  "industry" => $industry->id,
		  "current_price" => $row['current_price'],
		  "price_to_earning" => $row['price_to_earning'],
		  "peg_ratio" => $row['peg_ratio'],
		  "return_on_capital_employed" => $row['return_on_capital_employed'],
		  "return_on_equity" => $row['return_on_equity'],
		  "industry_pe" => $row['industry_pe'],
		  "debt_to_equity" => $row['debt_to_equity'],
		  "profit_growth" => $row['profit_growth'],
		  "market_capitalization" => $row['market_capitalization'],
		  "opm" => $row['opm'],
		  "price_to_sales" => $row['price_to_sales'],
		  "ev_ebitda" => $row['evebitda'],
		  "promoter_holding" => $row['promoter_holding'],
		  "change_in_promoter_holding" => $row['change_in_promoter_holding'],
		  "pledged_percentage" => $row['pledged_percentage'],
		  "dma_200" => $row['dma_200'],
		  "sales_growth_3years" => $row['sales_growth_3years'],
		  "sales_growth_5years" => $row['sales_growth_5years'],
		  "profit_growth_3years" => $row['profit_growth_3years'],
		  "profit_growth_5years" => $row['profit_growth_5years'],
		  "sales_growth" => $row['sales_growth'],
		  "average_return_on_equity_3years" => $row['average_return_on_equity_3years'],
		  "average_return_on_equity_5years" => $row['average_return_on_equity_5years'],
		  "piotroski_score" => $row['piotroski_score'],
		  "g_factor" => $row['g_factor'],
		  "roce3yr_avg" => $row['roce3yr_avg'],
		  "price_to_book_value" => $row['price_to_book_value'],
		  "average_return_on_capital_employed_3years" => $row['average_return_on_capital_employed_3years'],
		  "average_return_on_capital_employed_5years" => $row['average_return_on_capital_employed_5years'],
		  // "yoy_quarterly_sales_growth" => $row['yoy_quarterly_sales_growth'],
		  // "yoy_quarterly_profit_growth" => $row['yoy_quarterly_profit_growth'],
    	] ;

    	$where = [
    		"bse_code" => $row['bse_code'],
		  	"nse_code" => $row['nse_code'],
    	];

    	CompanyRepository::updateOrCreate($input, $where);


    	// dd($input);
        // return new User([
        //     'name' => $row[0],
        // ]);
    }
}
