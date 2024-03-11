<?php 

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Phone;

final class PhoneTransformer {
    public static function transform(Phone $phone, string $colour): array
    {
        return [ 
            'title' => $phone->title,
            'price' => $phone->price,
            'imageUrl' => $phone->imageUrl,
            'capacityMB' => $phone->capacityMB,
            'colour' => $colour,
            'availabilityText' => $phone->availabilityText,
            'isAvailable' => $phone->isAvailable,
            'shippingText' => $phone->shippingText,
            'shippingDate' => $phone->shippingDate?->format('Y-m-d'),
        ];
    }

    /**
     * Transform a de-duped array of Phones.
     *
     * @param Phone[] $phones
     * @return array
     */
    public static function transformMany(array $phones): array
    {
        $transformedPhones = [];
        $duplicationHashes = [];
        foreach ($phones as $phone) {
            foreach ($phone->colours as $colour) {
                if (!in_array($phone->getUniqueHash($colour), $duplicationHashes, true)) {
                    $transformedPhones[] = PhoneTransformer::transform($phone, $colour);
                    $duplicationHashes[] = $phone->getUniqueHash($colour);
                }
            }
        }

        return $transformedPhones;
    }
}