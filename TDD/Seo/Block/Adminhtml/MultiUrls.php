<?php

namespace TDD\Seo\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class MultiUrls extends AbstractFieldArray
{

    protected function _prepareToRender()
    {
        $this->addColumn('label', ['label' => __('Url Key'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Url Key');
    }
}