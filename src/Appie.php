<?php

namespace Appie;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class Appie
{
    const PRODUCTSORT_DATE_LAST_PURCHASE = 'dateLastPurchase';
    const PRODUCTSORT_NUMBER_OF_PURCHASES = 'numberOfTimesKassabon';

    /** @var MemberInterface */
    private $member;

    /** @var Client */
    private $client;

    public function __construct($memberId = null, $memberToken = null)
    {
        if ($memberId !== null && $memberToken !== null) {
            $this->member = new Member($memberId, $memberToken);
        } else {
            $this->member = new GuestMember();
        }
    }

    /**
     * @param $offset
     * @param $maxResults
     * @param string $sort one of the PRODUCTSORT constants
     */
    public function getProductsBoughtBefore($offset = 0, $maxResults = 25, $sort = self::PRODUCTSORT_NUMBER_OF_PURCHASES)
    {
        if (!in_array($sort, [self::PRODUCTSORT_DATE_LAST_PURCHASE, self::PRODUCTSORT_NUMBER_OF_PURCHASES])) {
            throw new \InvalidArgumentException('Invalid sort option provided. Should be either PRODUCTSORT_DATE_LAST_PURCHASE or PRODUCTSORT_NUMBER_OF_PURCHASES');
        }

        if ($this->member->isAnonymous()) {
            throw new \Exception('You need to be logged in to access your bought products');
        }

        $uri = new Uri('https://ms.ah.nl/rest/ah/v1/members/' . $this->member->getMemberID() . '/products/pbo');
        $uri = Uri::withQueryValue($uri, 'maxResults', $maxResults);
        $uri = Uri::withQueryValue($uri, 'sortProperty', $sort);
        $uri = Uri::withQueryValue($uri, 'offset', $offset);

        $uri = RequestSigner::sign($uri, $this->getApiClient(), $this->member->getMemberID());

        $request = new Request('GET', $uri, [
            'User-Agent' => 'Appie/4.4.0 Model/Galaxy+Nexus Android/5.0.2-API21 Member/' . $this->member->getMemberID(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Deliver-Errors-In-Json' => 'true',
            'X-ClientName' => 'android'
        ]);

        return json_decode($this->getClient()->send($request)->getBody());
    }

    public function getTopRecipes($maxResults = 25)
    {
        $uri = new Uri('https://ms.ah.nl/rest/ah/v1/recipe-util/top');
        $uri = Uri::withQueryValue($uri, 'maxResults', $maxResults);
        $uri = Uri::withQueryValue($uri, 'period', 'week');
        $uri = RequestSigner::sign($uri, $this->getApiClient(), $this->member->getMemberID());

        $request = new Request('GET', $uri, [
            'User-Agent' => 'Appie/4.4.0 Model/Galaxy+Nexus Android/5.0.2-API21 Member/' . $this->member->getMemberID(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Deliver-Errors-In-Json' => 'true',
            'X-ClientName' => 'android'
        ]);

        return json_decode($this->getClient()->send($request)->getBody());
    }


    private function getClient()
    {
        if ($this->client === null) {
            $config = [];
            if (!$this->member->isAnonymous()) {
                $config['auth'] = [$this->member->getMemberID(), $this->member->getPassword()];
            }

            $this->client = new Client($config);
        }

        return $this->client;
    }

    private function getApiClient()
    {
        return new \Appie\Client('android', '4.4.0', 'G00gMo81L');
    }

}
