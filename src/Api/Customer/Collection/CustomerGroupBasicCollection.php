<?php declare(strict_types=1);

namespace Shopware\Api\Customer\Collection;

use Shopware\Api\Customer\Struct\CustomerGroupBasicStruct;
use Shopware\Api\Entity\EntityCollection;

class CustomerGroupBasicCollection extends EntityCollection
{
    /**
     * @var CustomerGroupBasicStruct[]
     */
    protected $elements = [];

    public function get(string $uuid): ? CustomerGroupBasicStruct
    {
        return parent::get($uuid);
    }

    public function current(): CustomerGroupBasicStruct
    {
        return parent::current();
    }

    protected function getExpectedClass(): string
    {
        return CustomerGroupBasicStruct::class;
    }
}