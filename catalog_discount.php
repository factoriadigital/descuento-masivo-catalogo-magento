<?php

ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', '-1');

require_once 'abstract.php';

class FactoriaDigital_Shell_Catalog_Discount extends Mage_Shell_Abstract
{
    protected $_pageSize = 500;

    public function run()
    {
        $start = microtime(true);

        $params = getopt(null, ["discount:"]);
        $discount = isset($params['discount']) ? $params['discount'] : null;

        if (!is_null($discount) && $discount > 0) {
            $this->_applyDiscount($discount);
            $timeElapsed = microtime(true) - $start;
            echo "Time elapsed: ". number_format($timeElapsed, 2) .' seconds.'. PHP_EOL;
        } else {
            echo $this->usageHelp();
        }
    }

    public function usageHelp() {
        return <<<USAGE
Usage:  php -f scriptname.php [option]

Available options:

--discount=<% of discount>: Applies the % of the discount specified on that parameter. Parameter must be numeric without decimals.

USAGE;
    }

    protected function _getProgress($currentPage = null, $pages = null)
    {
        if(is_null($currentPage) || is_null($pages)) {
            $percentage = 0;
        } else {
            $percentage = ($currentPage * 100) / $pages;
        }

        echo "Progress: ".number_format($percentage, 2)."% \r";
    }

    protected function _applyDiscount($discount)
    {
        $pageSize = $this->_pageSize;

        $catalogProductCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->setPageSize($pageSize);

        $pages = $catalogProductCollection->getLastPageNumber();

        for($currentPage = 1; $currentPage <= $pages; $currentPage++) {
            $this->_getProgress($currentPage, $pages);

            $catalogProductCollection->setCurPage($currentPage);
            $catalogProductCollection->load();

            foreach($catalogProductCollection as $catalogProduct) {
                try {
                    $productId = $catalogProduct->getId();
                    $catalogProduct->load($productId);

                    $price      = $catalogProduct->getPrice();
                    $newPrice   = $price - ($price * ($discount / 100));

                    $catalogProduct->setPrice($newPrice);
                    $catalogProduct->getResource()->saveAttribute($catalogProduct, 'price');

                    Mage::log('Product ['.$productId.'] : ' . $price . ' => '.$newPrice, null, 'catalog_discount.log');

                } catch (Exception $e) {
                    echo 'Failed at product ID: '.$productId . ' with message: ' . PHP_EOL;
                    echo $e->getMessage() . PHP_EOL;
                    return;
                }
            }

            $catalogProductCollection->clear();
        }

        echo "Progress: 100.00%" . PHP_EOL;
        echo "COMPLETED!" . PHP_EOL;
    }


}

$shell = new FactoriaDigital_Shell_Catalog_Discount();
$shell->run();
