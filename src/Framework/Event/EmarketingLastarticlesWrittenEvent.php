<?php declare(strict_types=1);

namespace Shopware\Framework\Event;

use Shopware\Api\Write\WrittenEvent;

class EmarketingLastarticlesWrittenEvent extends WrittenEvent
{
    const NAME = 's_emarketing_lastarticles.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getEntityName(): string
    {
        return 's_emarketing_lastarticles';
    }
}