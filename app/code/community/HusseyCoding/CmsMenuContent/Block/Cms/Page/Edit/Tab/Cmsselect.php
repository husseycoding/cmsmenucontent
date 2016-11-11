<?php
class HusseyCoding_CmsMenuContent_Block_Cms_Page_Edit_Tab_Cmsselect
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $model = Mage::registry('cms_page');

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('menu_');

        $menufieldset = $form->addFieldset('cmsmenucontentpages_fieldset', array(
            'legend' => Mage::helper('cms')->__('Configure Menu Content'),
            'comment' => Mage::helper('cms')->__('Configure this page to display at least one menu of static block content.')
        ));
        
        $menufieldset->addField('is_menu_page', 'select', array(
            'name' => 'is_menu_page',
            'label' => Mage::helper('cms')->__('Menu Page'),
            'title' => Mage::helper('cms')->__('Menu Page'),
            'note' => Mage::helper('cms')->__('Use {{menucontent}} in content to set position, once for each section created using breaks.'),
            'values' => array(
                '1' => Mage::helper('cms')->__('Enabled'),
                '0' => Mage::helper('cms')->__('Disabled'),
            )
        ));
        
        $menufieldset->addField('block_order', 'hidden', array(
            'name' => 'block_order',
            'value' => ''
        ));
        
        $menufieldset->addField('menu_items', 'multiselect', array(
            'name' => 'menu_items',
            'label' => Mage::helper('cms')->__('Pages in Menu'),
            'title' => Mage::helper('cms')->__('Pages in Menu'),
            'values' => Mage::getSingleton('cmsmenucontent/system_config_source_cmsmenucontent')->toOptionArray()
        ));
        
        $linkfieldset = $form->addFieldset('cmsmenucontentlinks_fieldset', array(
            'legend' => Mage::helper('cms')->__('Configure Link Display'),
            'comment' => Mage::helper('cms')->__('Optionally style the display of the menu links.  Use HTML Colour e.g. 444444.')
        ));
        
        $linkfieldset->addField('link_colour', 'text', array(
            'name' => 'link_colour',
            'label' => Mage::helper('cms')->__('Link Colour'),
            'title' => Mage::helper('cms')->__('Link Colour')
        ));
        
        $linkfieldset->addField('hover_colour', 'text', array(
            'name' => 'hover_colour',
            'label' => Mage::helper('cms')->__('Link Hover Colour'),
            'title' => Mage::helper('cms')->__('Link Hover Colour')
        ));
        
        $linkfieldset->addField('active_colour', 'text', array(
            'name' => 'active_colour',
            'label' => Mage::helper('cms')->__('Link Active Colour'),
            'title' => Mage::helper('cms')->__('Link Active Colour')
        ));
        
        $linkfieldset->addField('ahover_colour', 'text', array(
            'name' => 'ahover_colour',
            'label' => Mage::helper('cms')->__('Link Active Hover Colour'),
            'title' => Mage::helper('cms')->__('Link Active Hover Colour')
        ));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return Mage::helper('cms')->__('Menu Content');
    }

    public function getTabTitle()
    {
        return Mage::helper('cms')->__('Menu Content');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/page/' . $action);
    }
}
