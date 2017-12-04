<?php declare(strict_types=1);

namespace Shopware\Country\Collection;

use Shopware\Api\Entity\EntityCollection;
use Shopware\Country\Struct\CountryStateTranslationBasicStruct;

class CountryStateTranslationBasicCollection extends EntityCollection
{
    /**
     * @var CountryStateTranslationBasicStruct[]
     */
    protected $elements = [];

    public function get(string $uuid): ? CountryStateTranslationBasicStruct
    {
        return parent::get($uuid);
    }

    public function current(): CountryStateTranslationBasicStruct
    {
        return parent::current();
    }

    public function getCountryStateUuids(): array
    {
        return $this->fmap(function (CountryStateTranslationBasicStruct $countryStateTranslation) {
            return $countryStateTranslation->getCountryStateUuid();
        });
    }

    public function filterByCountryStateUuid(string $uuid): CountryStateTranslationBasicCollection
    {
        return $this->filter(function (CountryStateTranslationBasicStruct $countryStateTranslation) use ($uuid) {
            return $countryStateTranslation->getCountryStateUuid() === $uuid;
        });
    }

    public function getLanguageUuids(): array
    {
        return $this->fmap(function (CountryStateTranslationBasicStruct $countryStateTranslation) {
            return $countryStateTranslation->getLanguageUuid();
        });
    }

    public function filterByLanguageUuid(string $uuid): CountryStateTranslationBasicCollection
    {
        return $this->filter(function (CountryStateTranslationBasicStruct $countryStateTranslation) use ($uuid) {
            return $countryStateTranslation->getLanguageUuid() === $uuid;
        });
    }

    protected function getExpectedClass(): string
    {
        return CountryStateTranslationBasicStruct::class;
    }
}