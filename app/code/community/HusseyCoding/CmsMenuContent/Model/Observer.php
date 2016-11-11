<?php
class HusseyCoding_CmsMenuContent_Model_Observer
{
    public function adminhtmlCmsPageSaveBefore($observer)
    {
        $page = $observer->getObject();
        $items = $page->getBlockOrder();
        $items = preg_replace('/^(sb,)+/', '', $items);
        $items = preg_replace('/(,sb)+$/', '', $items);
        $page->setMenuItems($items);
        $page->setBlockOrder($items);
    }
    
    public function adminhtmlCmsPageLoadAfter($observer)
    {
        $page = $observer->getObject();
        $page->setMenuItems(explode(',', $page->getMenuItems()));
    }
    
    public function frontendCmsPageRender($observer)
    {
        $page = $observer->getPage();
        if ($page->getIsMenuPage() && $page->getMenuItems()):
            $content = $page->getContent();
            $blocks = $page->getBlockOrder();
            if (strpos($content, '{{menucontent}}') !== false && $blocks):
                $blocks = explode(',', $blocks);
                $content = Mage::helper('cmsmenucontent')->addBlocks($blocks, $content, $page);
                $page->setContent($content);
            endif;
        else:
            $content = $page->getContent();
            if (strpos($content, '{{menucontent}}') !== false):
                $content = str_replace('{{menucontent}}', '', $content);
                $page->setContent($content);
            endif;
        endif;
    }
}