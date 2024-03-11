<?php 

declare(strict_types= 1);

namespace Tests\Unit\Services;

use App\Entity\Phone;
use App\Mapper\PhoneMapper;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class PhoneMapperTest extends TestCase {
    private Crawler $crawler;

    public function setUp(): void {
        $page = file_get_contents(__DIR__ ."/../../snapshots/phones-page-1.html");
        $this->crawler = new Crawler($page);
    }

    public function testProductGetsParsedIntoEntity()
    {
        // Arrange 
        $page = file_get_contents(__DIR__ ."/../../snapshots/phones-page-1.html");
        $crawler = new Crawler($page);

        $products = $crawler->filter('.product');
        $product1 = $products->eq(0);
        $product2 = $products->eq(1);
        $product3 = $products->eq(2);
        $product4 = $products->eq(3);
        $product5 = $products->eq(4);
        $product6 = $products->eq(5);

        // Act
        $entity1 = PhoneMapper::fromCrawler($product1)->toEntity();
        $entity2 = PhoneMapper::fromCrawler($product2)->toEntity();
        $entity3 = PhoneMapper::fromCrawler($product3)->toEntity();
        $entity4 = PhoneMapper::fromCrawler($product4)->toEntity();
        $entity5 = PhoneMapper::fromCrawler($product5)->toEntity();
        $entity6 = PhoneMapper::fromCrawler($product6)->toEntity();

        // Assert
        $this->assertInstanceOf(Phone::class, $entity1);
        $this->assertEquals('iPhone 11 Pro', $entity1->title);
        $this->assertEquals(64000, $entity1->capacityMB);
        $this->assertEquals('../images/iphone-11-pro.png', $entity1->imageUrl);
        $this->assertEquals(799.99, $entity1->price);
        $this->assertEquals('Out of Stock', $entity1->availabilityText);
        $this->assertEquals(false, $entity1->isAvailable);
        $this->assertEquals('', $entity1->shippingText);
        $this->assertEquals('', $entity1->shippingDate);

        $this->assertInstanceOf(Phone::class, $entity2);
        $this->assertEquals('iPhone 11', $entity2->title);
        $this->assertEquals(64000, $entity2->capacityMB);
        $this->assertEquals('../images/iphone-11.png', $entity2->imageUrl);
        $this->assertEquals(699.99, $entity2->price);
        $this->assertEquals('In Stock at B90 4SB', $entity2->availabilityText);
        $this->assertEquals(true, $entity2->isAvailable);
        $this->assertEquals('Unavailable for delivery', $entity2->shippingText);
        $this->assertEquals('', $entity2->shippingDate);

        $this->assertInstanceOf(Phone::class, $entity3);
        $this->assertEquals('iPhone 12 Pro Max', $entity3->title);
        $this->assertEquals(128000, $entity3->capacityMB);
        $this->assertEquals('../images/iphone-12-pro.png', $entity3->imageUrl);
        $this->assertEquals(1099.99, $entity3->price);
        $this->assertEquals('In Stock Online', $entity3->availabilityText);
        $this->assertEquals(true, $entity3->isAvailable);
        $this->assertEquals('Delivery by 11 Mar 2024', $entity3->shippingText);
        $this->assertEquals(CarbonImmutable::parse('2024-03-11'), $entity3->shippingDate);

        $this->assertInstanceOf(Phone::class, $entity4);
        $this->assertEquals('Samsung Galaxy S20', $entity4->title);
        $this->assertEquals(128000, $entity4->capacityMB);
        $this->assertEquals('../images/s-20.png', $entity4->imageUrl);
        $this->assertEquals(849.99, $entity4->price);
        $this->assertEquals('In Stock', $entity4->availabilityText);
        $this->assertEquals(true, $entity4->isAvailable);
        $this->assertEquals('Available on 2024-03-17', $entity4->shippingText);
        $this->assertEquals(CarbonImmutable::parse('2024-03-17'), $entity4->shippingDate);

        $this->assertInstanceOf(Phone::class, $entity5);
        $this->assertEquals('Huawei P30 Pro', $entity5->title);
        $this->assertEquals(128000, $entity5->capacityMB);
        $this->assertEquals('../images/p-30.png', $entity5->imageUrl);
        $this->assertEquals(699.99, $entity5->price);
        $this->assertEquals('In Stock', $entity5->availabilityText);
        $this->assertEquals(true, $entity5->isAvailable);
        $this->assertEquals('Free Shipping', $entity5->shippingText);
        $this->assertEquals(null, $entity5->shippingDate);

        $this->assertInstanceOf(Phone::class, $entity6);
        $this->assertEquals('Nokia 3310', $entity6->title);
        $this->assertEquals(100000, $entity6->capacityMB);
        $this->assertEquals('../images/nokia-3310.png', $entity6->imageUrl);
        $this->assertEquals(99.99, $entity6->price);
        $this->assertEquals('Out of Stock', $entity6->availabilityText);
        $this->assertEquals(false, $entity6->isAvailable);
        $this->assertEquals('Delivery from Tuesday 9th Apr 2024', $entity6->shippingText);
        $this->assertEquals(CarbonImmutable::parse('2024-04-09'), $entity6->shippingDate);
    }
}