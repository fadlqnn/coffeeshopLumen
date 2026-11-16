<?php
namespace App\Traits;

trait ApiResponse
{
    // Default JSON. XML jika ?format=xml atau header Accept mengandung xml
    protected function respond(array $data, int $status = 200)
    {
        $request = app('request');
        $format  = strtolower((string) $request->query('format', ''));

        if ($format === '') {
            $format = (strpos((string) $request->header('Accept'), 'xml') !== false) ? 'xml' : 'json';
        }

        if ($format === 'xml') {
            $xml = new \SimpleXMLElement('<response/>');
            $this->arrayToXml($data, $xml);
            return response($xml->asXML(), $status)->header('Content-Type', 'application/xml');
        }

        return response()->json($data, $status);
    }

    private function arrayToXml(array $data, \SimpleXMLElement $xml)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                $child = $xml->addChild($key);
                $this->arrayToXml($value, $child);
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value));
            }
        }
    }
}