<?php
class VC_AdvancedCategoriesMenu_Model_System_Config_Source_Layout_Menu extends Varien_Object
{
    protected $_options = null;

    public function toOptionArray()
    {
        if ($this->_options === null) {
            $this->_options = array();
			$this->_options[] = array(
				'value' => 1,
				'label' => Mage::helper('cms')->__('SubCategories'),
			);
			$this->_options[] = array(
				'value' => 2,
				'label' => Mage::helper('cms')->__('Category tree'),
			);
			
        }
        return $this->_options;
    }

}