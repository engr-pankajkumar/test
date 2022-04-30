<?php

return [

     'columnHeader' => [
          "Company" => 'company_name',
          "M CAP" => 'market_capitalization',
          "IND PE" => 'industry_pe',
          "PE" => 'price_to_earning',
          "PEG" => 'peg_ratio',
          "D/E" => 'debt_to_equity',
          "P/S" => 'price_to_sales',
          "ROCE" => 'return_on_capital_employed',
          "ROE" => 'return_on_equity',
         
          "EV/EBITDA" => 'ev_ebitda',
          "P/B" => 'price_to_book_value',
          "Pro Gro" => 'profit_growth',

          "CMP" => 'current_price',
          "DMA 200" => 'dma_200',
         
          "OPM" => 'opm',
          
          "PH" => 'promoter_holding',
          "PH Change" => 'change_in_promoter_holding',
          "Pledged %" => 'pledged_percentage',
          
          "SG3" => 'sales_growth_3years',
          "SG5" => 'sales_growth_5years',
          "PG3" => 'profit_growth_3years',
          "PG5" => 'profit_growth_5years',
          "SG" => 'sales_growth',
          "ROE 3 AVG" => 'average_return_on_equity_3years',
          "ROE 5 AVG" => 'average_return_on_equity_5years',
          "P Score" => 'piotroski_score',
          "G Factor" => 'g_factor',
          // "roce3yr_avg" => 'roce3yr_avg',
          
          "ROCE 3 AVG" => 'average_return_on_capital_employed_3years',
          "ROCE 5 AVG" => 'average_return_on_capital_employed_5years',
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
    
];


 