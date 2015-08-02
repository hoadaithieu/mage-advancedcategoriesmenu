<?php
class VC_AdvancedCategoriesMenu_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getFilterUrl() {
		return $this->_getUrl('vc_advancedcategoriesmenu/index/filter');
	}
	
	public function getCleanUrl() {
		return $this->getFilterUrl();
	}
	
	
	public function getItemQuery($ar) {
		$rs = array();
		if ($query = Mage::registry('vc_query')) {
			$ar += $query;
			
		}
		
		if (is_array($ar) && count($ar) > 0) {
			foreach ($ar as $k => $v) {
				$rs[] = $k.'='.$v;
			}
		}
		return implode('&', $rs);
	}
	
	public function getDeleteQuery($ar, $removeKey = '') {
		$rs = array();
		if ($query = Mage::registry('vc_query')) {
			$ar += $query;
		}
		
		if (isset($ar[$removeKey])) {
			unset($ar[$removeKey]);
		}
		
		if (is_array($ar) && count($ar) > 0) {
			foreach ($ar as $k => $v) {
				$rs[] = $k.'='.$v;
			}
		}
		return implode('&', $rs);
	}
	
	public function getContainer() {
		$container = '';
		$layout = !Mage::registry('vc_layout') ? Mage::app()->getLayout()->getBlock('root')->getTemplate() : Mage::registry('vc_layout');
		if (strpos($layout, '3columns')) {
			$container =  Mage::getStoreConfig('vc_advancedcategoriesmenu/general/container_3columns');
		} else if (strpos($layout, '2columns-left')) {
			$container =  Mage::getStoreConfig('vc_advancedcategoriesmenu/general/container_2columns_left');
		}
	
		return $container;
	}
	
	public function getItemUrl($ar) {
		return Mage::registry('vc_category_url').'?'.(is_string($ar) ? $ar: $this->getItemQuery($ar));
	}
	
	public function getClearedUrl() {
		return Mage::registry('vc_category_url');
	}
	
	public function renderFilter($block, $items, $level) {
		$rs = "<ol>";
		foreach ($items as $_item):
			$rs .= '<li style="margin-left:'.($level*10).'px">';
				 if ($_item->getCount() > 0): 
					$rs .= '<a href="'.$block->urlEscape($_item->getUrl()).'">';
					$rs .= $_item->getLabel();
					if ($block->shouldDisplayProductCount()):
						$rs .='<span class="count">('.$_item->getCount().')</span>';
					endif;
					$rs .= '</a>';
				else:
				
					$rs .= '<span>';
					$rs .= $_item->getLabel();
					if ($block->shouldDisplayProductCount()): 
						$rs .= '<span class="count">('.$_item->getCount().')</span>';
					endif;
					$rs .= '</span>';
				endif;
				
				if (count($_item->getChild()) > 0) {
					$rs .= $this->renderFilter($block, $_item->getChild(), $level+1);
				}
			$rs .= '</li>';
		endforeach;
		$rs .="</ol>";
		return $rs;
	}
}