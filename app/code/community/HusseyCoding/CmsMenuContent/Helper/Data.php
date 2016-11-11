<?php
class HusseyCoding_CmsMenuContent_Helper_Data extends Mage_Core_Helper_Abstract
{
    public $css;
    public $js;
    
    public function addBlocks($blocks, $content, $page)
    {
        $blocks = $this->_separateBlocks($blocks);
        $blocks = $this->_getValidateBlocks($blocks, $page->getStoreId());
        $linkcolour = $this->validateHtmlColour($page->getLinkColour());
        $hovercolour = $this->validateHtmlColour($page->getHoverColour());
        $activecolour = $this->validateHtmlColour($page->getActiveColour());
        $ahovercolour = $this->validateHtmlColour($page->getAhoverColour());
        
        if (!empty($blocks)):
            $itemshtml = $this->_getItemsHtml($blocks);
            $html = $this->_getItemsCss($linkcolour, $hovercolour, $activecolour, $ahovercolour);
            foreach ($blocks as $section => $block):
                $html .= '<div class="cmsmenucontent_container">';
                $html .= '<div class="cmsmenucontent_menu">';
                $html .= $itemshtml[$section];
                $html .= '</div>';
                $html .= '</div>';
                $content = preg_replace('/{{menucontent}}/', $html, $content, 1);
                $html = '';
            endforeach;
            $content .= $this->_getItemsJs($blocks);
            
            return $content;
        endif;
        
        return str_replace('{{menucontent}}', '', $content);
    }
    
    private function _getValidateBlocks($blocks, $pagescope)
    {
        $return = array();
        $cmsall = in_array('0', $pagescope);
        foreach ($blocks as $id => $section):
            foreach ($section as $block):
                $block = Mage::getModel('cms/block')->load((int) $block);
                if ($block):
                    if ($block->getIsActive() && $block->getUseInMenuPage()):
                        if (!$cmsall):
                            $blockscope = $block->getStoreId();
                            $compare = array_diff($blockscope, $pagescope);
                            if (count($blockscope) > count($compare) || in_array('0', $blockscope)):
                                $return[$id][] = $block;
                            endif;
                        else:
                            $return[$id][] = $block;
                        endif;
                    endif;
                endif;
            endforeach;
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
    
    private function _getItemsHtml($blocks)
    {
        $html = array();
        $key = 0;
        foreach ($blocks as $k => $section):
            foreach ($section as $block):
                $key++;
                $title = $this->getBlockMenuTitle($block);
                $class = strpos($title, '?') !== false ? ' question-mark-indent' : ' information-indent';
                $html[$k][] = '<a class="blocklink' . $class . '" id="blocklink_' . $key . '" href="javascript:void(0)">' . $title . '</a>';
                $html[$k][] = '<div class="blockcontent" id="blockcontent_' . $key . '">' . $block->getContent() . '</div>';
            endforeach;
            $html[$k] = implode('', $html[$k]);
        endforeach;
        
        return $html;
    }
    
    public function getBlockMenuTitle($block)
    {
        return $block->getLinkText() ? $block->getLinkText() : $block->getTitle();
    }
    
    private function _getItemsCss($linkcolour, $hovercolour, $activecolour, $ahovercolour)
    {
        $css = '';
        if ($linkcolour || $hovercolour || $activecolour):
            $css .= '<style type="text/css">';
            if ($linkcolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink { color:' . $linkcolour . '; }';
            if ($hovercolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink:hover { color:' . $hovercolour . '; }';
            if ($activecolour):
                $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink_active { color:' . $activecolour . '; }';
                $css .= ' .cmsmenucontent_container .cmsmenucontent_menu .information-indent:before, .cmsmenucontent_container .cmsmenucontent_menu .question-mark-indent:before { background-color:' . $activecolour . '; }';
                $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink_active:after { border-color:rgba(' . $activecolour . ', 0); border-top-color:' . $activecolour . '; }';
                $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink:after { border-color:rgba(' . $activecolour . ', 0); border-right-color:' . $activecolour . '; }';
            endif;
            if ($ahovercolour) $css .= ' .cmsmenucontent_container .cmsmenucontent_menu a.blocklink_active:hover { color:' . $ahovercolour . '; }';
            $css .= ' </style>';
        endif;
        
        return $css;
    }
    
    private function _getItemsJs($blocks)
    {
        $js = '<script type="text/javascript">';
        $js .= ' var thismenucontent = new menucontent();';
        $js .= ' thismenucontent.menuitems = {};';
        $key = 0;
        foreach ($blocks as $block):
            foreach ($block as $blocks):
                $key++;
                $js .= ' thismenucontent.menuitems.blocklink_' . $key . ' = "blockcontent_' . $key . '";';
            endforeach;
        endforeach;
        $js .= ' </script>';
        
        return $js;
    }
    
    private function _separateBlocks($blocks)
    {
        $return = array();
        $section = array();
        foreach ($blocks as $block):
            if ($block == 'sb'):
                if (!empty($section)):
                    $return[] = $section;
                    $section = array();
                endif;
            elseif (!empty($block)):
                $section[] = $block;
            endif;
        endforeach;
        
        if (!empty($section)) $return[] = $section;
        
        return !empty($return) ? $return : $blocks;
    }
}