<?php
include "app/code/core/Mage/Catalog/controllers/CategoryController.php";
class VC_AjaxLayeredNavigation_IndexController extends Mage_Catalog_CategoryController{
	
    public function indexAction() {
		$this->loadLayout();
		$this->renderLayout(); 
    }
	
	public function filterAction() {
		$result = array();
		if ($category = $this->_initCatagory()) {
			Mage::register('vc_query', $this->getRequest()->getQuery());
			Mage::register('vc_category_url', $category->getUrl());
			Mage::register('vc_layout', $this->getRequest()->getPost('layout'));
			
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }

            Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');

            if (!$category->hasChildren()) {
                $update->addHandle('catalog_category_layered_nochildren');
            }

            $this->addActionLayoutHandles();
            $update->addHandle($category->getLayoutUpdateHandle());
            $update->addHandle('CATEGORY_' . $category->getId());
			$update->addHandle('vc_ajaxlayerednavigation_catalog_category_layered');
			
			
            $this->loadLayoutUpdates();

            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }

            $this->generateLayoutXml()->generateLayoutBlocks();
            // apply custom layout (page) template once the blocks are generated
			if (strpos($this->getRequest()->getPost('layout'), '3columns')) {
				$this->getLayout()->getBlock('root')->setTemplate('vc_ajaxlayerednavigation/container-3columns.phtml');
				
			} else if (strpos($this->getRequest()->getPost('layout'), '2columns-left')) {
				$this->getLayout()->getBlock('root')->setTemplate('vc_ajaxlayerednavigation/container-2columns-left.phtml');
				
			} 

			
			$result['content'] = $this->getLayout()->getBlock('root')->toHtml();
        }
        elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
		
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	
}