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

namespace WellCommerce\ClientBundle\Repository;

use Doctrine\ORM\Query\Expr;
use WellCommerce\CoreBundle\Repository\AbstractEntityRepository;

/**
 * Class ClientWishlistRepository
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientWishlistRepository extends AbstractEntityRepository implements ClientWishlistRepositoryInterface
{
    public function getDataSetQueryBuilder()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->groupBy('product.id');
        $queryBuilder->leftJoin('product', 'product');
        $queryBuilder->leftJoin('product.translations', 'product_translation');
        $queryBuilder->leftJoin('product.categories', 'categories');
        $queryBuilder->leftJoin('product.producer', 'producers');
        $queryBuilder->leftJoin('product.sellPriceTax', 'sell_tax');
        $queryBuilder->leftJoin('categories.translations', 'categories_translation');
        $queryBuilder->leftJoin('producers.translations', 'producers_translation');
        $queryBuilder->leftJoin('product.productPhotos', 'gallery', Expr\Join::WITH, 'gallery.mainPhoto = :mainPhoto');
        $queryBuilder->leftJoin('gallery.photo', 'photos');
        $queryBuilder->leftJoin('product.statuses', 'statuses');
        $queryBuilder->leftJoin('product.shops', 'product_shops');
        $queryBuilder->leftJoin('statuses.translations', 'statuses_translation');
        $queryBuilder->setParameter('mainPhoto', 1);

        return $queryBuilder;
    }
}