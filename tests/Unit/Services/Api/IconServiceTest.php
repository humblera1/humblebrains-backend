<?php

namespace Tests\Unit\Services\Api;

use App\Services\Api\IconService;
use PHPUnit\Framework\TestCase;


class IconServiceTest extends TestCase
{
    public function test_updates_fill_attribute_with_specified_color()
    {
        $iconService = new IconService();

        $originalSvg = <<<SVG
<svg width="100" height="100">
    <circle cx="50" cy="50" r="40" fill="red" />
    <rect x="10" y="10" width="30" height="30" fill="blue" />
</svg>
SVG;

        $expectedSvg = <<<SVG
<svg width="100" height="100">
    <circle cx="50" cy="50" r="40" fill="green"/>
    <rect x="10" y="10" width="30" height="30" fill="green"/>
</svg>
SVG;

        $result = $iconService->updateSvgFill($originalSvg, 'green');

        $this->assertNotFalse($result, 'The method should not return false.');
        $this->assertXmlStringEqualsXmlString($expectedSvg, $result, 'The SVG content was not updated correctly.');
    }

    public function test_updates_fill_attribute_with_default_color()
    {
        $iconService = new IconService();

        $originalSvg = <<<SVG
<svg width="100" height="100">
    <circle cx="50" cy="50" r="40" fill="red" />
    <rect x="10" y="10" width="30" height="30" fill="blue" />
</svg>
SVG;

        $expectedSvg = <<<SVG
<svg width="100" height="100">
    <circle cx="50" cy="50" r="40" fill="currentColor"/>
    <rect x="10" y="10" width="30" height="30" fill="currentColor"/>
</svg>
SVG;

        $result = $iconService->updateSvgFill($originalSvg);

        $this->assertNotFalse($result, 'The method should not return false.');
        $this->assertXmlStringEqualsXmlString($expectedSvg, $result, 'The SVG content was not updated correctly.');
    }

    public function test_updates_fill_attribute_with_invalid_xml()
    {
        $iconService = new IconService();

        $invalidSvg = <<<SVG
<svg width="100" height="100">
    <circle cx="50" cy="50" r="40" fill="red"
    <rect x="10" y="10" width="30" height="30" fill="blue" />
</svg>
SVG;

        $result = $iconService->updateSvgFill($invalidSvg, 'green');

        $this->assertFalse($result, 'The method should return false for invalid XML.');
    }

    // todo: add one more test
//    public function test_logs_errors_when_exceptions_occur()
//    {
//        Log::shouldReceive('error')->once();
//
//        $invalidSvgContent = '<svg><rect></svg>';
//        $result = $this->updateSvgFill($invalidSvgContent);
//
//        $this->assertFalse($result);
//    }
}
