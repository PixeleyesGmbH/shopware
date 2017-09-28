<?php declare(strict_types=1);

namespace Shopware\Framework\Write\Resource;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Write\Field\IntField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\Resource;

class EmotionCategoriesResource extends Resource
{
    protected const EMOTION_ID_FIELD = 'emotionId';
    protected const CATEGORY_ID_FIELD = 'categoryId';

    public function __construct()
    {
        parent::__construct('s_emotion_categories');

        $this->fields[self::EMOTION_ID_FIELD] = (new IntField('emotion_id'))->setFlags(new Required());
        $this->fields[self::CATEGORY_ID_FIELD] = (new IntField('category_id'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            \Shopware\Framework\Write\Resource\EmotionCategoriesResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, TranslationContext $context, array $errors = []): \Shopware\Framework\Event\EmotionCategoriesWrittenEvent
    {
        $event = new \Shopware\Framework\Event\EmotionCategoriesWrittenEvent($updates[self::class] ?? [], $context, $errors);

        unset($updates[self::class]);

        if (!empty($updates[\Shopware\Framework\Write\Resource\EmotionCategoriesResource::class])) {
            $event->addEvent(\Shopware\Framework\Write\Resource\EmotionCategoriesResource::createWrittenEvent($updates, $context));
        }

        return $event;
    }
}