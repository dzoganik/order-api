<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

/**
 * Class OrderFixture
 * @package App\DataFixtures
 */
class OrderFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $item = new OrderItem();
        $item
            ->setProductId('product7777')
            ->setTitle('Product 7777')
            ->setPrice(100.50)
            ->setQuantity(1.5);

        $order = new Order();
        $order
            ->setOrderId('777777')
            ->setPartnerId('partner77777')
            ->setDeliveryDate(new DateTime('2022-08-01'))
            ->setOrderValue(100.50)
            ->addOrderItem($item);

        $manager->persist($order);
        $manager->flush();
    }
}
