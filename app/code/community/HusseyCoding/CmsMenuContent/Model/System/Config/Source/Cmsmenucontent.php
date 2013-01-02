<?php
class HusseyCoding_CmsMenuContent_Model_System_Config_Source_Cmsmenucontent
{
    public function toOptionArray()
    {
        $selects = array();
        $pages = Mage::getModel('cms/block')->getCollection();
        
        $scope = Mage::app()->getRequest()->getParam('page_id');
        $scope = Mage::getModel('cms/page')->load($scope);
        $scope = $scope->getStoreId();
        
        $resource = Mage::getSingleton('core/resource');
        $select = $pages->getSelect();
        $select
            ->where('main_table.is_active = ?', 1)
            ->where('main_table.use_in_menu_page = ?', 1);
        
        if (!in_array('0', $scope)):
            $blockscope = $scope;
            $blockscope[] = '0';
            $select
                ->join(
                    array('store' => $resource->getTableName('cms/block_store')),
                    'main_table.block_id = store.block_id',
                    array('store_id')
                )
                ->where('store.store_id IN (?)', $blockscope)
                ->group('block_id');
        endif;
        
        foreach ($pages as $page):
            $selects[] = array('value' => $page->getId(), 'label' => $page->getTitle());
        endforeach;
        
        return $selects;
    }
}