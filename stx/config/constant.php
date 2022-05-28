<?php

return [

     'columnHeader' => [
          "Company" => 'company_name',
          "M CAP" => 'market_capitalization',
          "IND PE" => 'industry_pe',
          "PE" => 'price_to_earning',
          "PEG" => 'peg_ratio',
          "DE" => 'debt_to_equity',
          "PS" => 'price_to_sales',
          "ROCE" => 'return_on_capital_employed',
          "ROE" => 'return_on_equity',
         
          "EV/EBITDA" => 'ev_ebitda',
          "P/B" => 'price_to_book_value',
          "Pro Gro" => 'profit_growth',
          "SG" => 'sales_growth',
          "OPM" => 'opm',

          // "CMP" => 'current_price',
          // "DMA 200" => 'dma_200',
     
          
          // "PH" => 'promoter_holding',
          "PH Change" => 'change_in_promoter_holding',
          "Pledged %" => 'pledged_percentage',
          
          "SG3" => 'sales_growth_3years',
          "SG5" => 'sales_growth_5years',
          "PG3" => 'profit_growth_3years',
          "PG5" => 'profit_growth_5years',
         
          "ROE 3 AVG" => 'average_return_on_equity_3years',
          "ROE 5 AVG" => 'average_return_on_equity_5years',
          "P Score" => 'piotroski_score',
          "G Factor" => 'g_factor',
          // "roce3yr_avg" => 'roce3yr_avg',
          
          "ROCE 3 AVG" => 'average_return_on_capital_employed_3years',
          "ROCE 5 AVG" => 'average_return_on_capital_employed_5years',
     ],

     'columnHeaderGroup' => [
          'CompanyInfo' => [
               // "Company" => 'company_name',
               "M CAP" => 'market_capitalization',
          ],
          
          "Ratios" => [
               "IND PE" => 'industry_pe',
               "PE" => 'price_to_earning',
               "PEG" => 'peg_ratio',
               "DE" => 'debt_to_equity',
               "PS" => 'price_to_sales',
               "ROCE" => 'return_on_capital_employed',
               "ROE" => 'return_on_equity',
               "EV/EBITDA" => 'ev_ebitda',
               "P/B" => 'price_to_book_value',
          ],

          "Profits" => [
               "Pro Gro" => 'profit_growth',
               "PG3" => 'profit_growth_3years',
               "PG5" => 'profit_growth_5years',
               "OPM" => 'opm',
          ],

          "Sales" => [
               "SG" => 'sales_growth',
               "SG3" => 'sales_growth_3years',
               "SG5" => 'sales_growth_5years',
          ],

          'Promoter' => [
               "PH Change" => 'change_in_promoter_holding',
               "Pledged %" => 'pledged_percentage',
          ],

          'Avgs' => [
               "ROE 3 AVG" => 'average_return_on_equity_3years',
               "ROE 5 AVG" => 'average_return_on_equity_5years',
               "ROCE 3 AVG" => 'average_return_on_capital_employed_3years',
               "ROCE 5 AVG" => 'average_return_on_capital_employed_5years',
          ],

          'Scores' => [
               "P Score" => 'piotroski_score',
               "G Factor" => 'g_factor',
          ],
           
     ],

     'searchOrder' => [
          'debt_to_equity' => 'ASC',
          'peg_ratio' => 'ASC',
          'return_on_capital_employed' => 'DESC',
          'return_on_equity' => 'DESC',
          'profit_growth' => 'DESC',
     ],

     'notNullOrder' => [
          'bse_code',
          'nse_code',
          'peg_ratio',
     ],

     'filter' => [
          // "M CAP" =>[ 'min' =>10000, 'max' => 2000000, 'step' => 100000 ],
          "PEG"  => [ 'min' =>0, 'max' => 3, 'step' => 0.5 ],
          "DE"  => [ 'min' =>0, 'max' => 2, 'step' => 0.5 ],
          "ROCE" => [ 'min' =>0, 'max' => 80, 'step' => 10 ],
          "ROE" =>  [ 'min' =>0, 'max' => 80, 'step' => 10 ],
          "PS" =>  [ 'min' =>0, 'max' => 30, 'step' => 5],
     ],

     'filterOptionPrefix' =>   [
          'lt' => 'lt', // less than
          'lte' => 'lte',// less than or equal to
          'gt' => 'gt',// greater than
          'gte' => 'gte',// greater than or equal to
      ],
    
];



 