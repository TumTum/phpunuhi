<?php

declare(strict_types=1);

namespace PHPUnuhi\Services\Loaders\Xml;

use Exception;
use SimpleXMLElement;

class XmlLoader implements XmlLoaderInterface
{
    /**
     * @throws Exception
     */
    public function loadXML(string $filename): SimpleXMLElement
    {
        if (!file_exists($filename)) {
            throw new Exception('Configuration file not found: ' . $filename);
        }

        $rootXmlString = (string)file_get_contents($filename);

        $xml = null;

        try {
            $xml = simplexml_load_string($rootXmlString);
        } catch (Exception $e) {
        }

        if (!$xml instanceof SimpleXMLElement) {
            throw new Exception('Could not parse XML file: ' . $filename);
        }

        return $xml;
    }
}
