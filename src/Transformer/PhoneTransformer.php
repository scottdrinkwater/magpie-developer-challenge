<?php 

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Phone;

final class PhoneTransformer
{
    public function __construct(private string $siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    public function transform(Phone $phone, string $colour): array
    {
        return [ 
            'title' => $phone->title,
            'price' => $phone->price,
            'imageUrl' => $this->getExternalImageSrc($phone->imageUrl),
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
     * @param  Phone[] $phones
     * @return array
     */
    public function transformMany(array $phones): array
    {
        $transformedPhones = [];
        $duplicationHashes = [];
        foreach ($phones as $phone) {
            foreach ($phone->colours as $colour) {
                if (!in_array($phone->getUniqueHash($colour), $duplicationHashes, true)) {
                    $transformedPhones[] = $this->transform($phone, $colour);
                    $duplicationHashes[] = $phone->getUniqueHash($colour);
                }
            }
        }

        return $transformedPhones;
    }

    private function getBaseImageUrl($traverseUpDirectoryCount = 0): string
    {
        // Removing trailing slash on URL first, if applicable
        $imageUrl = rtrim($this->siteUrl, '/');
        // Traverse up directory x amount of times. Not foolproof bit fairly extendable going forwards.
        $imageUrl = explode('/', $imageUrl);
        array_splice($imageUrl, (int) (0 - $traverseUpDirectoryCount));
        
        return implode('/', $imageUrl) . '/'; 
    }

    private function getExternalImageSrc(string $imageSrc): string
    {
        // How many directories to traverse up.
        $traverseUpDirectoryCount = substr_count($imageSrc, '../');

        return str_replace(
            '../', 
            $this->getBaseImageUrl($traverseUpDirectoryCount), 
            $imageSrc
        );
    }
}