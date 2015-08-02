<?php
include 'app/code/core/Mage/Catalog/Block/Layer/Filter/Category.php';

class VC_AdvancedCategoriesMenu_Block_Catalog_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Category
{
    /**
     * Initialize Price filter module
     *
     */
    public function __construct()
    {
        parent::__construct();
		if (Mage::getStoreConfig('vc_advancedcategoriesmenu/general/enable')) {
			$this->setTemplate('vc_advancedcategoriesmenu/catalog/layer/category/filter.phtml');
		}
    }
}
