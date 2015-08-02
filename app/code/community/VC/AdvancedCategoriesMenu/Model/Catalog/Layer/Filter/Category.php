<?php
include 'app/code/core/Mage/Catalog/Model/Layer/Filter/Category.php';
class VC_AdvancedCategoriesMenu_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
	
    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {

			$category = $this->getCategory();
            $data = $this->_getLItemsData($category->getId());
            
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
	
	protected function _getLItemsData($catId = 0) {
		$data = array();
		$category = Mage::getModel('catalog/category')
			->load($catId);
			
		if (!$category) return $data;
		$categories = $category->getChildrenCategories();
		
		$this->getLayer()->getProductCollection()
			->addCountToCategories($categories);

		
		foreach ($categories as $category) {
			
			if ($category->getIsActive() && $category->getProductCount()) {
				$data[] = array(
					'label' => Mage::helper('core')->escapeHtml($category->getName()),
					'value' => $category->getId(),
					'count' => $category->getProductCount(),
					'child' => $this->_getLItemsData($category->getId())
				);
			}
		}
		return $data;
	}
	
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        
		$items=array();
        foreach ($data as $itemData) {
            $items[] = $this->_createItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
				
				(Mage::getStoreConfig('vc_advancedcategoriesmenu/general/enable') ? $this->_initLItems($itemData['child']) : array())
            );
        }
        $this->_items = $items;
		
		//$this->_items = $data;
        return $this;
    }
	
	protected function _initLItems($child) {
		$items=array();
        foreach ($child as $itemData) {
            $items[] = $this->_createItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
				
				$this->_initLItems($itemData['child'])
            );
        }
		return $items;
	}
	
    protected function _createItem($label, $value, $count=0, $child = array())
    {
		
        return Mage::getModel('catalog/layer_filter_item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count)
			->setChild($child);
    }
	
}
