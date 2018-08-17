<?php

namespace PolderKnowledge\CountryDropdown;

use function Functional\map;
use function Functional\pluck;
use function Functional\sort;
use League\ISO3166\ISO3166;
use Zend\Form\Element\Select;

class CountrySelect extends Select
{
    /**
     * The countries which should be on top so the end-user can find them easily
     *
     * @var string[]
     */
    protected $topCountryCodes = [];

    public function setTopCountryCodes(array $topCountryCodes)
    {
        $this->topCountryCodes = $topCountryCodes;
    }

    public function getTopCountryCodes(): array
    {
        return $this->topCountryCodes;
    }

    /**
     * Usually called by Zend\Form\Factory
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['top_country_codes'])) {
             $this->setTopCountryCodes($options['top_country_codes']);
        }
    }

    /**
     * Usually called by the FormElementManager
     * After all the options have been set
     * and just before handing the element to the user's form or fieldset
     */
    public function init()
    {
        parent::init();

        $this->valueOptions = self::generateValueOptions($this->getTopCountryCodes());
    }

    public static function generateValueOptions(array $topCountryCodes): array
    {
        $bottomCountryCodes = array_diff(self::generateAllCountryCodes(), $topCountryCodes);
        $bottomOptions = self::countryCodesToOptions($bottomCountryCodes);
        $topOptions = self::countryCodesToOptions($topCountryCodes);

        if (empty($topOptions)) {
            return $bottomOptions;
        }

        $separatorOption = [
            'label' => '──────────',
            'disabled' => true,
            'value' => null,
        ];

        return array_merge(
            $topOptions,
            [$separatorOption],
            $bottomOptions
        );
    }

    /**
     * @return string[]
     */
    public static function generateAllCountryCodes(): array
    {
        return pluck(new ISO3166(), ISO3166::KEY_ALPHA2);
    }

    public static function countryCodesToOptions(array $countryCodes): array
    {
        $options = map($countryCodes, [__CLASS__, 'countryCodeToOption']);

        return sort($options, function (array $left, array $right) {
            return $left['label'] <=> $right['label'];
        });
    }

    public static function countryCodeToOption(string $code): array
    {
        return [
            'value' => $code,
            'label' => \Locale::getDisplayRegion("-$code"),
        ];
    }
}
