<?php

namespace PolderKnowledge\CountryDropdown\Test;

use PHPUnit\Framework\TestCase;
use PolderKnowledge\CountryDropdown\CountrySelect;
use Zend\Form\Factory;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\View\Helper\FormSelect;
use Zend\ServiceManager\ServiceManager;

class CountrySelectTest extends TestCase
{
    protected function setUp()
    {
        \Locale::setDefault('de-DE');
    }

    public function testCountryCodeToOption()
    {
        $option = CountrySelect::countryCodeToOption('NZ');

        $this->assertEquals([
            'value' => 'NZ',
            'label' => 'Neuseeland'
        ], $option);
    }

    public function testCountriesAreSortedAlphabeticallyByLabel()
    {
        $options = CountrySelect::countryCodesToOptions(['CD', 'DK', 'AR']);

        $this->assertEquals([
            [
                'value' => 'AR',
                'label' => 'Argentinien',
            ], [
                'value' => 'DK',
                'label' => 'Dänemark',
            ], [
                'value' => 'CD',
                'label' => 'Kongo-Kinshasa',
            ],
        ], $options);
    }

    public function testSeparatorIsInserted()
    {
        $options = CountrySelect::generateValueOptions(['DE']);

        $top3options = array_slice($options, 0, 3);

        $this->assertEquals([
            [
                'value' => 'DE',
                'label' => 'Deutschland',
            ], [
                'value' => null,
                'label' => '──────────',
                'disabled' => true,
            ], [
                'value' => 'AF',
                'label' => 'Afghanistan',
            ],
        ], $top3options);
    }

    public function testCreateElementViaFactory()
    {
        $factory = new Factory();

        /** @var CountrySelect $countrySelect */
        $countrySelect = $factory->createElement([
            'type' => CountrySelect::class,
            'options' => [
                'top_country_codes' => ['DE']
            ]
        ]);

        $this->assertInstanceOf(CountrySelect::class, $countrySelect);
        $this->assertEquals(['DE'], $countrySelect->getTopCountryCodes());
    }

    public function testCreateElementViaManager()
    {
        $formElementManager = new FormElementManagerV3Polyfill(new ServiceManager());

        /** @var CountrySelect $countrySelect */
        $countrySelect = $formElementManager->get(CountrySelect::class, [
            'top_country_codes' => ['DE']
        ]);

        $this->assertInstanceOf(CountrySelect::class, $countrySelect);
        $this->assertEquals(['DE'], $countrySelect->getTopCountryCodes());

        // test if ->init() has been called
        $this->assertTrue(count($countrySelect->getValueOptions()) > 100);
    }
}
