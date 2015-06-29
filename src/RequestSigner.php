<?php

namespace Appie;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class RequestSigner
{
    public static function sign(UriInterface $uri, Client $client, $memberID)
    {
        $uri = Uri::withQueryValue($uri, 'clientName', $client->getName());
        $uri = Uri::withQueryValue($uri, 'clientVersion', $client->getVersion());

        $digest = self::calculateDigestOverRequestPath($uri->getPath() . '?' . $uri->getQuery(), null, $memberID, $client->getDigestKey());
        $uri = Uri::withQueryValue($uri, 'digest', $digest);

        return $uri;
    }

    private static function calculateDigestOverRequestPath($requestUrl, $body = null, $memberID, $digestKey)
    {
        $dataToDigest = $requestUrl;

        if ($body !== null) {
            $dataToDigest .= $body;
        }

        $dataToDigest .= $memberID . $digestKey;

        return sha1($dataToDigest);
    }
}
