DROP TABLE IF EXISTS `company_info`;
CREATE TABLE `company_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `sector` int(10) NOT NULL,
  `industry` int(10) NOT NULL,
  `bse_code` varchar(100) DEFAULT NULL,
  `nse_code` varchar(100) DEFAULT NULL,
  `current_price` decimal(8,2) DEFAULT NULL,
  `dma_200` decimal(8,2) DEFAULT NULL,
  
  `price_to_earning` decimal(8,2) DEFAULT NULL,
  `peg_ratio` decimal(6,2) DEFAULT NULL,
  `return_on_capital_employed` decimal(6,2) DEFAULT NULL,
  `return_on_equity` decimal(6,2) DEFAULT NULL,
  `debt_to_equity` decimal(6,2) DEFAULT NULL,
  
  `price_to_sales` decimal(6,2) DEFAULT NULL,
  
  `industry_pe` decimal(6,2) DEFAULT NULL,
  
  `profit_growth` decimal(12,2) DEFAULT NULL,
  `opm` decimal(8,2) DEFAULT NULL,
  `market_capitalization` decimal(12,2) DEFAULT NULL,
  `ev_ebitda` decimal(8,2) DEFAULT NULL,
  `promoter_holding` decimal(12,2) DEFAULT NULL,
  `pledged_percentage` decimal(12,2) DEFAULT NULL,
  `change_in_promoter_holding` decimal(6,2) DEFAULT NULL,
  
  `sales_growth_3years` decimal(8,2) DEFAULT NULL,
  `sales_growth_5years` decimal(8,2) DEFAULT NULL,
  `profit_growth_3years` decimal(12,2) DEFAULT NULL,
  `profit_growth_5years` decimal(12,2) DEFAULT NULL,
  `sales_growth` decimal(12,2) DEFAULT NULL,
  `average_return_on_equity_3years` decimal(12,2) DEFAULT NULL,
  `average_return_on_equity_5years` decimal(12,2) DEFAULT NULL,
  
  `g_factor` decimal(6,2) DEFAULT NULL,
  `piotroski_score` decimal(6,2) DEFAULT NULL,
  `roce3yr_avg` decimal(6,2) DEFAULT NULL,
  
  `price_to_book_value` decimal(6,2) DEFAULT NULL,
  `average_return_on_capital_employed_3years` decimal(6,2) DEFAULT NULL,
  `average_return_on_capital_employed_5years` decimal(6,2) DEFAULT NULL,
  
  
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mst_sector`;	
CREATE TABLE `mst_sector` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sector_name` varchar(255) NOT NULL,
  `status` tinyint(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mst_industry`;
CREATE TABLE `mst_industry` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `sector_id` int(10) unsigned NOT NULL,
  `industry_name` varchar(255) NOT NULL,
  `status` tinyint(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

