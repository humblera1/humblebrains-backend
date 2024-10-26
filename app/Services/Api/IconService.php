<?php

namespace App\Services\Api;

use App\Models\Icon;
use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Support\Facades\Storage;

class IconService
{
    public function getRandomIconUrls(int $amount)
    {
        $icons = Icon::select('name', 'path')->inRandomOrder()->take($amount)->get();

        return $icons->map(function ($icon) {

            return [
                'name' => $icon->name,
                'src' => Storage::url($icon->path),
            ];
        })->toArray();
    }

    /**
     * Updates the fill attribute of all elements in the SVG content to the specified color.
     *
     * @param string $svgContent The SVG content to be updated.
     * @param string $color The color to set for the fill attribute. Defaults to 'currentColor'.
     * @return bool | string the XML, or false if an error occurred.
     */
    public function updateSvgFill(string $svgContent, string $color = 'currentColor'): bool | string
    {
        libxml_use_internal_errors(true);

        try {
            $dom = new DOMDocument('1.0', 'UTF-8');

            $dom->loadXML($svgContent);

            $errors = libxml_get_errors();

            libxml_clear_errors();

            // why? cause libxml can't do it itself
            if (!empty($errors)) {
                throw new Exception('Invalid SVG content was provided.');
            }

            $xpath = new DOMXPath($dom);
            $elements = $xpath->query('//*[@fill]');

            foreach ($elements as $element) {
                $element->setAttribute('fill', $color);
            }

            // Save only the content of the root element to avoid the XML declaration
            $updatedContent = $dom->saveXML($dom->documentElement);

            if ($updatedContent === false) {
                throw new Exception('Failed to save updated SVG content.');
            }
        } catch (Exception $e) {
            // todo: Log::error($e->getMessage());

            return false;
        }

        return $updatedContent;
    }
}
