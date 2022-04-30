<?php
    namespace App\Modules\Solr\Seeders;

    class StockSeeds
    {
        private $id;
        private $name;
        private $current_price;

        private $sectorId;
        // private $sectorName;
        private $industryId;
        // private $industryName;

        private $price_to_earning;
        private $peg_ratio;
        private $return_on_capital_employed;

        private $return_on_equity;
        private $debt_to_equity;
        private $market_capitalization;
        


        public function __construct( $input)
        {
            $this->id = $input->id;
            $this->name = $input->company_name;
            $this->current_price = $input->current_price;

            $this->sectorId = $input->sector;
            $this->industryId = $input->industry;

            $this->price_to_earning = $input->price_to_earning;
            $this->peg_ratio = $input->peg_ratio;
            $this->return_on_capital_employed = $input->return_on_capital_employed;

            $this->return_on_equity = $input->return_on_equity;
            $this->debt_to_equity = $input->debt_to_equity;
            $this->market_capitalization = $input->market_capitalization;
        }

        public function seed($doc)
        {
            // dd($doc);
            $doc->id = $this->id;
            $doc->name = $this->name;
            $doc->current_price = $this->current_price;

            $doc->sector_id = $this->sectorId;
            $doc->industry_id = $this->industryId;

            $doc->price_to_earning = $this->price_to_earning;
            $doc->peg_ratio = $this->peg_ratio;
            $doc->return_on_capital_employed = $this->return_on_capital_employed;

            $doc->return_on_equity = $this->return_on_equity;
            $doc->debt_to_equity = $this->debt_to_equity;
            $doc->market_capitalization = $this->market_capitalization;
            return $doc;
        }
    }

