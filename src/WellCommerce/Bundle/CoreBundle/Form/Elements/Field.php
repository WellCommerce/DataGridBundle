<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\Form\Elements;

use WellCommerce\Bundle\CoreBundle\Form\Dependency;
use WellCommerce\Bundle\CoreBundle\Form\Node;
use WellCommerce\Bundle\CoreBundle\Form\Rules\LanguageUnique;

/**
 * Class Field
 *
 * @package WellCommerce\Bundle\CoreBundle\Form\Elements
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Field extends Node
{
    protected $_value = '';

    public function isValid($values = [])
    {
        if (!isset($this->attributes['rules']) || !is_array($this->attributes['rules'])) {
            return true;
        }
        $result = true;
        foreach ($this->attributes['rules'] as $rule) {
            if (isset($this->_value) && is_array($this->_value)) {
                foreach ($this->_value as $i => $value) {
                    $skip = false;
                    if (isset($this->attributes['dependencies']) && is_array($this->attributes['dependencies'])) {
                        foreach ($this->attributes['dependencies'] as $dependency) {
                            if ((($dependency->type == Dependency::HIDE) && $dependency->evaluate($value, $i)) || (($dependency->type == Dependency::SHOW) && !$dependency->evaluate($value, $i)) || (($dependency->type == Dependency::IGNORE) && $dependency->evaluate($value, $i))) {
                                $skip = true;
                                break;
                            }
                        }
                    }
                    if (!$skip) {
                        if ($rule instanceof LanguageUnique) {
                            $rule->setLanguage($i);
                        }
                        if (($checkResult = $rule->check($value)) !== true) {
                            if (!isset($this->attributes['error']) || !is_array($this->attributes['error'])) {
                                $this->attributes['error'] = ($i > 0) ? array_fill(0, $i, '') : [];
                            } elseif ($i > 0) {
                                $this->attributes['error'] = $this->attributes['error'] + array_fill(0, $i, '');
                            }
                            $this->attributes['error'][$i] = $checkResult;
                            $result                         = false;
                        }
                    }
                }
            } else {
                if (isset($this->attributes['dependencies']) && is_array($this->attributes['dependencies'])) {
                    foreach ($this->attributes['dependencies'] as $dependency) {
                        if ((($dependency->type == Dependency::HIDE) && $dependency->evaluate($this->_value)) || (($dependency->type == Dependency::SHOW) && !$dependency->evaluate($this->_value)) || (($dependency->type == Dependency::IGNORE) && $dependency->evaluate($this->_value))) {
                            return $result;
                        }
                    }
                }
                if (($checkResult = $rule->check($this->_value)) !== true) {
                    $this->attributes['error'] = $checkResult;
                    $result                     = false;
                }
            }
        }

        return $result;
    }

    public function populate($value)
    {
        $value        = $this->filter($value);
        $this->_value = $value;
    }

    public function getValue()
    {
        if (!isset($this->_value)) {
            return null;
        }

        return $this->_value;
    }

    protected function formatDefaultsJs()
    {
        $values = $this->getValue();
        if (empty($values)) {
            return '';
        }
        if (is_array($values)) {
            return 'asDefaults: ' . json_encode($values);
        } else {
            return 'sDefault: ' . json_encode($values);
        }
    }

    protected function formatRulesJs()
    {
        $rules = [];
        foreach ($this->attributes['rules'] as $rule) {
            $rules[] = $rule->render();
        }

        return 'aoRules: [' . implode(', ', $rules) . ']';
    }
}
