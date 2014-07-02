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
namespace WellCommerce\Plugin\Attribute\Model;

use WellCommerce\Core\Component\Model\AbstractModel;
use WellCommerce\Core\Component\Model\ModelInterface;
use WellCommerce\Core\Component\Model\TranslatableModelInterface;

/**
 * Class Attribute
 *
 * @package WellCommerce\Plugin\Attribute\Model
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeGroup extends AbstractModel implements ModelInterface
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'attribute_group';

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['id'];

    /**
     * {@inheritdoc}
     */
    public function translation()
    {
        return $this->hasMany(__NAMESPACE__ . '\AttributeGroupTranslation');
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationXmlMapping()
    {
        return __DIR__ . '/../Resources/config/validation.xml';
    }
}
