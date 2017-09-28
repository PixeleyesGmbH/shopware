<?php declare(strict_types=1);
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Framework\Write\Field;

use Shopware\Framework\Validation\ConstraintBuilder;
use Shopware\Framework\Write\FieldAware\ConstraintBuilderAware;
use Shopware\Framework\Write\FieldAware\FilterRegistryAware;
use Shopware\Framework\Write\FieldAware\PathAware;
use Shopware\Framework\Write\FieldAware\ValidatorAware;
use Shopware\Framework\Write\FieldException\InvalidFieldException;
use Shopware\Framework\Write\Filter\Filter;
use Shopware\Framework\Write\Filter\FilterRegistry;
use Shopware\Framework\Write\Filter\HtmlFilter;
use Shopware\Framework\Write\Resource;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LongTextField extends Field implements PathAware, ConstraintBuilderAware, FilterRegistryAware, ValidatorAware
{
    /**
     * @var ConstraintBuilder
     */
    private $constraintBuilder;

    /**
     * @var FilterRegistry
     */
    private $filterRegistry;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var string
     */
    private $storageName;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $storageName
     */
    public function __construct(string $storageName)
    {
        $this->storageName = $storageName;
    }

    /**
     * @param string $type
     * @param string $key
     * @param null   $value
     *
     * @return \Generator
     */
    public function __invoke(string $type, string $key, $value = null): \Generator
    {
        switch ($type) {
            case Resource::FOR_INSERT:
                $this->validate($this->getInsertConstraints(), $key, $value);
                break;
            case Resource::FOR_UPDATE:
                $this->validate($this->getUpdateConstraints(), $key, $value);
                break;
            default:
                throw new \DomainException(sprintf('Could not understand %s', $type));
        }

        yield $this->storageName => $this->getFilter()->filter($value);
    }

    /**
     * {@inheritdoc}
     */
    public function setConstraintBuilder(ConstraintBuilder $constraintBuilder): void
    {
        $this->constraintBuilder = $constraintBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterRegistry(FilterRegistry $filterRegistry): void
    {
        $this->filterRegistry = $filterRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(string $path = ''): void
    {
        $this->path = $path;
    }

    /**
     * @param array  $constraints
     * @param string $fieldName
     * @param $value
     */
    private function validate(array $constraints, string $fieldName, $value)
    {
        $violationList = new ConstraintViolationList();

        foreach ($constraints as $constraint) {
            $violations = $this->validator
                ->validate($value, $constraint);

            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $violationList->add(
                    new ConstraintViolation(
                        $violation->getMessage(),
                        $violation->getMessageTemplate(),
                        $violation->getParameters(),
                        $violation->getRoot(),
                        $fieldName,
                        $violation->getInvalidValue(),
                        $violation->getPlural(),
                        $violation->getCode(),
                        $violation->getConstraint(),
                        $violation->getCause()
                    )
                );
            }
        }

        if (count($violationList)) {
            throw new InvalidFieldException($this->path . '/' . $fieldName, $violationList);
        }
    }

    /**
     * @return array
     */
    private function getInsertConstraints(): array
    {
        return $this->constraintBuilder
            ->isNotBlank()
            ->isString()
            ->getConstraints();
    }

    /**
     * @return array
     */
    private function getUpdateConstraints(): array
    {
        return $this->constraintBuilder
            ->isString()
            ->getConstraints();
    }

    /**
     * @return Filter
     */
    private function getFilter(): Filter
    {
        return $this->filterRegistry
            ->get(HtmlFilter::class);
    }
}