<?php
class HusseyCoding_CmsMenuContent_Helper_Data extends Mage_Core_Helper_Abstract
{
    public $css;
    public $js;
    
    public function addBlocks($blocks, $content, $page)
    {
        $blocks = $this->getValidateBlocks($blocks, $page->getStoreId());
        $linkcolour = $this->validateHtmlColour($page->getLinkColour());
        $hovercolour = $this->validateHtmlColour($page->getHoverColour());
        $activecolour = $this->validateHtmlColour($page->getActiveColour());
        $ahovercolour = $this->validateHtmlColour($page->getAhoverColour());
        
        if ($blocks):
            $itemshtml = $this->getItemsHtml($blocks);
            
            $html = $this->getItemsCss($linkcolour, $hovercolour, $activecolour, $ahovercolour);
            
            $html .= '<div class="cmsmenucontent_container">';
            
            $html .= '<div class="cmsmenucontent_menu">';
            $html .= $itemshtml['linkshtml'];
            $html .= '</div>';
            
            $html .= '<div class="cmsmenucontent_content">';
            $html .= $itemshtml['contenthtml'];
            $html .= '</div>';
            
            $html .= '</div>';
            
            $html .= $this->getItemsJs($blocks);

            $html = str_replace('{{menucontent}}', $html, $content);
            
            return $html;
        endif;
        
        return str_replace('{{menucontent}}', '', $content);
    }
    
    private function getValidateBlocks($blocks, $pagescope)
    {
        $return = array();
        $cmsall = in_array('0', $pagescope);
        foreach ($blocks as $block):
            $block = Mage::getModel('cms/block')->load((int) $block);
            if ($block):
                if ($block->getIsActive() && $block->getUseInMenuPage()):
                    if (!$cmsall):
                        $blockscope = $block->getStoreId();
                        $compare = array_diff($blockscope, $pagescope);
                        if (count($blockscope) > count($compare) || in_array('0', $blockscope)):
                            $return[] = $block;
                        endif;
                    else:
                        $return[] = $block;
                    endif;
                endif;
            endif;
        endforeach;
        
        return $return;
    }
    
    public function validateHtmlColour($colour)
    {
        if (strpos($colour, '#') === 0):
            $colour = substr($colour, 1);
        endif;
        if (preg_match('/^[0-9a-fA-F]+$/', $colour)):
            if (strlen($colour) == 6 || strlen($colour) == 3):
                return '#' . $colour;
            endif;
        endif;
        
        return false;
    }
    
    private function getItemsHtml($blocks)
    {
        $menuhtml = array();
        $contenthtml = array();
        foreach ($blocks as $key => $block):
            $key++;
            $title = $this->getBlockMenuTitle($block);
            $menuhtml[] = '<a class="blocklink" id="blocklink_' . $key . '" href="javascript:void(0)">' . $title . '</a>';
            $contenthtml[] = '<div class="blockcontent" id="blockcontent_' . $key . '">' . $block->getContent() . '</div>';
        endforeach;
        
        $menuhtml = implode('', $menuhtml);
        $contenthtml = implode('', $contenthtml);
        
        return array('linkshtml' => $menuhtml, 'contenthtml' => $contenthtml);
    }
    
    public function getBlockMenuTitle($block)
    {
        return $block->getLinkText() ? $block->getLinkText() : $block->getTitle();
    }
    
    private function getItemsCss($linkcolour, $hovercolour, $activecolour, $ahovercolour)
    {
        $css = '';
        if ($linkcolour || $hovercolour || $activecolour):
            $css .= '<style type="text/css">';
            if ($linkcolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink { color:' . $linkcolour . '; }';
            if ($hovercolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink:hover { color:' . $hovercolour . '; }';
            if ($activecolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink_active { color:' . $activecolour . '; }';
            if ($ahovercolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink_active:hover { color:' . $ahovercolour . '; }';
            $css .= ' </style>';
        endif;
        
        return $css;
    }
    
    private function getItemsJs($blocks)
    {
        $js = '<script type="text/javascript">';
        $js .= ' var thismenucontent = new menucontent();';
        $js .= ' thismenucontent.menuitems = {};';
        foreach ($blocks as $key => $blocks):
            $key++;
            $js .= ' thismenucontent.menuitems.blocklink_' . $key . ' = "blockcontent_' . $key . '";';
        endforeach;
        $js .= ' </script>';
        
        return $js;
    }
}