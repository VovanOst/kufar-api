<?php

namespace API\Kufar\Adverts;

use API\Kufar\Finder;
use Dionchaika\Http\Uri;
use InvalidArgumentException;
use API\Kufar\AdvertInterface;
use Psr\Http\Message\RequestInterface;
use Dionchaika\Http\Factory\RequestFactory;

/**
 * The flat advert class.
 */
class Flat implements AdvertInterface
{
    /**
     * The house type select.
     */
    const HOUSE_TYPE = [

        1 => 'Панельный',
        2 => 'Монолитный',
        3 => 'Кирпичный',
        4 => 'Блочный',
        5 => 'Каркасный'

    ];

    /**
     * The bathroom type select.
     */
    const BATHROOM_TYPE = [

        1 => 'Раздельный',
        2 => 'Совмещенный',
        3 => 'Два',
        4 => 'Три'

    ];

    /**
     * The balcony type select.
     */
    const BALCONY_TYPE = [

        1 => 'Есть',
        2 => 'Нет',
        3 => 'Лоджия',
        4 => 'Два'

    ];

    /**
     * The currency type select.
     */
    const CURRENCY_TYPE = [

        'BYR' => 'р.',
        'USD' => '$',
        'EUR' => '€'

    ];

    /**
     * The array
     * of flat advert data.
     *
     * @var mixed[]
     */
    protected $data = [

        'ad' => [

            'is_new_image'      => [],
            'language'          => 'ru',
            'subject'           => null,
            'category'          => 1010,
            'type'              => 'sell',
            'rooms'             => null,
            'floor'             => null,
            'size'              => null,
            'size_living_space' => null,
            'size_kitchen'      => null,
            'house_type'        => null,
            'bathroom'          => null,
            'balcony'           => null,
            'year_built'        => null,
            'condition'         => 1,
            'body'              => null,
            'price'             => null,
            'currency'          => null,
            'region'            => null,
            'area'              => null,
            'address'           => null,
            'name'              => null,
            'email'             => null,
            'phone'             => null,
            'contact_person'    => null,
            'company_address'   => null,
            'import_link'       => null,
            'vat_number'        => null,
            'company_number'    => null,
            'company_ad'        => null,
            'coordinates'       => null,
            'address_tags'      => null,
            'remuneration_type' => 1,
            'images'            => []

        ],
        'delivery' => null

    ];

    /**
     * @param string      $subject
     * @param int         $rooms
     * @param string      $body
     * @param int         $price
     * @param string      $currency
     * @param int         $region
     * @param int         $area
     * @param string      $address
     * @param int|null    $floor
     * @param float|null  $size
     * @param float|null  $sizeLivingSpace
     * @param float|null  $sizeKitchen
     * @param int|null    $houseType
     * @param int|null    $bathroom
     * @param int|null    $balcony
     * @param int|null    $yearBuilt
     * @param string[]    $images
     * @param string[]    $phones
     * @param string|null $contactPerson
     * @param string|null $importLink
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $subject,
        int $rooms,
        string $body,
        int $price,
        string $currency,
        int $region,
        int $area,
        string $address,
        ?int $floor             = null,
        ?float $size            = null,
        ?float $sizeLivingSpace = null,
        ?float $sizeKitchen     = null,
        ?int $houseType         = null,
        ?int $bathroom          = null,
        ?int $balcony           = null,
        ?int $yearBuilt         = null,
        array $images           = [],
        array $phones           = [],
        ?string $contactPerson  = null,
        ?string $importLink     = null
    ) {
        if ('' === $subject) {
            throw new InvalidArgumentException(
                'Required field is not defined or empty: subject!'
            );
        }

        $subject = mb_substr($subject, 0, 50);

        if (20 > mb_strlen($body)) {
            throw new InvalidArgumentException(
                'Required field is not defined or empty: body!'
            );
        }

        $body = mb_substr($body, 0, 4000);

        if (5 < $rooms) {
            $rooms = 5;
        }

        if (1980 > $yearBuilt) {
            $yearBuilt = 1980;
        } else if (2025 < $yearBuilt) {
            $yearBuilt = 2025;
        }

        if (15 < count($images)) {
            $images = array_slice($images, 0, 15);
        }

        $phones = array_map(function ($phone) {
            return preg_replace('/[^\d]/', '', $phone);
        }, $phones);

        if (3 < count($phones)) {
            $phones = array_slice($phones, 0, 3);
        }

        $this->data['ad']['subject']           = $subject;
        $this->data['ad']['rooms']             = $rooms;
        $this->data['ad']['body']              = $body;
        $this->data['ad']['price']             = $price;
        $this->data['ad']['currency']          = $currency;
        $this->data['ad']['region']            = $region;
        $this->data['ad']['area']              = $area;
        $this->data['ad']['address']           = $address;
        $this->data['ad']['floor']             = $floor;
        $this->data['ad']['size']              = $size;
        $this->data['ad']['size_living_space'] = $sizeLivingSpace;
        $this->data['ad']['size_kitchen']      = $sizeKitchen;
        $this->data['ad']['house_type']        = $houseType;
        $this->data['ad']['bathroom']          = $bathroom;
        $this->data['ad']['balcony']           = $balcony;
        $this->data['ad']['year_built']        = $yearBuilt;
        $this->data['ad']['images']            = $images;
        $this->data['ad']['phone']             = implode(',', $phones);
        $this->data['ad']['contact_person']    = $contactPerson;
        $this->data['ad']['import_link']       = $importLink;
    }

    /**
     * Find the house type by name.
     *
     * @param string $houseTypeName
     * @return int
     */
    public static function findHouseTypeByName(string $houseTypeName): int
    {
        return Finder::suggestKey($houseTypeName, static::HOUSE_TYPE);
    }

    /**
     * Find the bathroom type by name.
     *
     * @param string $bathroomTypeName
     * @return int
     */
    public static function findBathroomTypeByName(string $bathroomTypeName): int
    {
        return Finder::suggestKey($bathroomTypeName, static::BATHROOM_TYPE);
    }

    /**
     * Find the balcony type by name.
     *
     * @param string $balconyTypeName
     * @return int
     */
    public static function findBalconyTypeByName(string $balconyTypeName): int
    {
        return Finder::suggestKey($balconyTypeName, static::BALCONY_TYPE);
    }

    /**
     * Find the currency type by name.
     *
     * @param string $currencyTypeName
     * @return string
     */
    public static function findCurrencyTypeByName(string $currencyTypeName): string
    {
        return Finder::suggestKey($currencyTypeName, static::CURRENCY_TYPE);
    }

    /**
     * Set an account info.
     *
     * @param mixed[] $accountInfo
     * @return self
     */
    public function setAccountInfo(array $accountInfo): self
    {
        $this->data['ad']['name']            = $accountInfo['name'];
        $this->data['ad']['email']           = $accountInfo['email'];
        $this->data['ad']['company_address'] = $accountInfo['company_address'];
        $this->data['ad']['vat_number']      = $accountInfo['vat_number'];
        $this->data['ad']['company_number']  = $accountInfo['company_number'];
        $this->data['ad']['company_ad']      = $accountInfo['company_ad'];

        if (null === $this->data['ad']['phone']) {
            $this->data['ad']['phone'] = $accountInfo['phone'];
        }

        if (null === $this->data['ad']['contact_person']) {
            $this->data['ad']['contact_person'] = $accountInfo['contact_person'];
        }

        return $this;
    }

    /**
     * Set an address info.
     *
     * @param array $addressInfo
     * @return self
     */
    public function setAddressInfo(array $addressInfo): self
    {
        $this->data['ad']['coordinates']  = $addressInfo['coordinates'];
        $this->data['ad']['address_tags'] = $addressInfo['address_tags'];

        return $this;
    }

    /**
     * Get the HTTP request for the flat advert.
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        $data = $this->data;
        foreach ($data['ad'] as $key => $value) {
            if (null === $value) {
                unset($data['ad'][$key]);
            }
        }

        $uri = new Uri('https://www.kufar.by/react/api/cre/ad-insertion/v1/processing/insert');
        return (new RequestFactory)
            ->createJsonRequest('POST', $uri, $data, [\JSON_PRETTY_PRINT, \JSON_UNESCAPED_SLASHES, \JSON_UNESCAPED_UNICODE])
            ->withHeader('X-segmentation', 'routing=web_ad_insertion;application=ad_insertion;platform=web');
    }
}
