<?php
class HusseyCoding_CmsMenuContent_Block_Cms_Block_Edit_AdminhtmlForm extends Mage_Adminhtml_Block_Cms_Block_Edit_Form
{
    protected function _prepareForm()
    {
        parent::_prepareForm();
        
        $model = Mage::registry('cms_block');

        $form = $this->getForm();
        
        $menufieldset = $form->addFieldset('menucontent_fieldset', array(
            'legend' => Mage::helper('cms')->__('Configure Link Display'),
            'class' => 'fieldset-wide',
            'comment' => Mage::helper('cms')->__('Configure how this block will display on CMS pages configured as menu pages.')
        ));

        $menufieldset->addField('use_in_menu_page', 'select', array(
            'label' => Mage::helper('cms')->__('Enabled For Menu Pages'),
            'title' => Mage::helper('cms')->__('Enabled For Menu Pages'),
            'name' => 'use_in_menu_page',
            'options' => array(
                '1' => Mage::helper('cms')->__('Yes'),
                '0' => Mage::helper('cms')->__('No'),
            )
        ));
        
        $menufieldset->addField('link_text', 'text', array(
            'name' => 'link_text',
            'label' => Mage::helper('cms')->__('Link Text'),
            'title' => Mage::helper('cms')->__('Link Text'),
            'note' => Mage::helper('cms')->__('Block title is used by default')
        ));
        
        $form->setValues($model->getData());
        
        return $this;
    }
}