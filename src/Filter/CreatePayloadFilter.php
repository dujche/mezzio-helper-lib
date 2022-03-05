<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Filter;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Date;
use Laminas\Validator\Digits;
use Laminas\Validator\StringLength;

abstract class CreatePayloadFilter extends InputFilter
{
    protected function addStringValidator(string $fieldName, int $maxLength, int $minLength = 1): void
    {
        $this->add(
            [
                'name' => $fieldName,
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => $minLength,
                            'max' => $maxLength,
                            'messages' => [
                                StringLength::TOO_LONG =>
                                    $fieldName . ' must be less than ' . ($maxLength + 1) . ' characters long',
                                StringLength::TOO_SHORT =>
                                    $fieldName . ' must be more than ' . ($minLength - 1) . ' characters long',
                            ]
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * @param string $fieldName
     * @return void
     */
    protected function addIntegerValidator(string $fieldName): void
    {
        $this->add(
            [
                'name' => $fieldName,
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => Digits::class,
                    ],
                ],
            ]
        );
    }

    /**
     * @param string $fieldName
     * @return void
     */
    protected function addDateValidator(string $fieldName): void
    {
        $this->add(
            [
                'name' => $fieldName,
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class,
                        'options' => [],
                    ],
                ],
                'validators' => [
                    [
                        'name' => Date::class,
                    ],
                ],
            ]
        );
    }
}
