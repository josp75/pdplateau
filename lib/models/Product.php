<?php


/*
 * ECOMMERCE PDP
 * Vente en ligne pour la PDP
 * @copyright IMEDIATIS Ltd 2022
 * @author Jospin MAMBOU (jospin@imediatis.net)
 * @package WEBADMIN 2.0
 * @client PDP
 * @version 1.0
 *
 */

class Product extends ProductDBA
{
    const SEARCHID = 0;
    const SEARCHTOKEN = 1;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string|null
     */
    private $reference;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string|null
     */
    private $longname;

    /**
     * @var Family
     */
    private $family;

    /**
     * @var boolean
     */
    private $instock;

    /**
     * @var int|null
     */
    private $minorder;

    /**
     * @var int|null
     */
    private $maxorder;

    /**
     * @var string
     */
    private $features;

    /**
     * @var string|null
     */
    private $pictures;

    /**
     * @var string
     */
    private $mainpicture;

    /**
     * @var int
     */
    private $unitcost;

    public function __construct(?string $reference = null, string $nickname = null, ?string $longname = null, ?Family $family =null,
                                bool $instock = null, ?int $minorder = null, ?int $maxorder = null, ?string $features = null, ?string $pictures = null, string $mainpicture = null, int $unitcost =null){
        $this->reference = $reference;
        $this->nickname = $nickname;
        $this->longname = $longname;
        $this->family = $family;
        $this->instock = $instock;
        $this->minorder = $minorder;
        $this->maxorder = $maxorder;
        $this->features = $features;
        $this->pictures = $pictures;
        $this->mainpicture = $mainpicture;
        $this->unitcost = $unitcost;
        parent::__construct($this);

    }

    /**
     * @param $object
     * @return bool
     */
    public static function instanceOf($object): bool
    {
        return isset($object) && !empty(array_filter(get_object_vars($object))) && $object instanceof self;
    }

    /**
     * @return string
     */
    public static function _data(): string
    {
        return parent::DATA;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     */
    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @param string|null $nickname
     */
    public function setNickname(?string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string|null
     */
    public function getLongname(): ?string
    {
        return $this->longname;
    }

    /**
     * @param string|null $longname
     */
    public function setLongname(?string $longname): void
    {
        $this->longname = $longname;
    }

    /**
     * @return Family
     */
    public function getFamily(): ?Family
    {
        return $this->family;
    }

    /**
     * @param Family $family
     */
    public function setFamily(Family $family): void
    {
        $this->family = $family;
    }

    /**
     * @return bool
     */
    public function isInstock(): ?bool
    {
        return $this->instock;
    }

    /**
     * @param bool $instock
     */
    public function setInstock(?bool $instock): void
    {
        $this->instock = $instock;
    }

    /**
     * @return int|null
     */
    public function getMinorder(): ?int
    {
        return $this->minorder;
    }

    /**
     * @param int|null $minorder
     */
    public function setMinorder(?int $minorder): void
    {
        $this->minorder = $minorder;
    }

    /**
     * @return int|null
     */
    public function getMaxorder(): ?int
    {
        return $this->maxorder;
    }

    /**
     * @param int|null $maxorder
     */
    public function setMaxorder(?int $maxorder): void
    {
        $this->maxorder = $maxorder;
    }

    /**
     * @return string
     */
    public function getFeatures(): ?string
    {
        return $this->features;
    }

    /**
     * @param string $features
     */
    public function setFeatures(string $features): void
    {
        $this->features = $features;
    }

    /**
     * @return string|null
     */
    public function getPictures(): ?string
    {
        return $this->pictures;
    }

    /**
     * @param string|null $pictures
     */
    public function setPictures(?string $pictures): void
    {
        $this->pictures = $pictures;
    }

    /**
     * @return string|null
     */
    public function getMainpicture(): ?string
    {
        return $this->mainpicture;
    }

    /**
     * @param string $mainpicture
     */
    public function setMainpicture(string $mainpicture): void
    {
        $this->mainpicture = $mainpicture;
    }

    /**
     * @return int
     */
    public function getUnitcost(): ?int
    {
        return $this->unitcost;
    }

    /**
     * @param int $unitcost
     */
    public function setUnitcost(int $unitcost): void
    {
        $this->unitcost = $unitcost;
    }



    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~  LINKING METHODS  -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return array
     */
    private function toArray(): array
    {
        return [
            'token' => $this->token,
            'reference' => U::getString($this->reference),
            'nickname' => U::getString($this->nickname),
            'longname' => U::getString($this->longname),
            'family' => Family::instanceOf($this->family) ? $this->family->tabularize(): null,
            'instock' => $this->instock,
            'minorder' => $this->minorder,
            'maxorder' => $this->maxorder,
            'features' =>  U::getString($this->features),
            'pictures' =>  U::getString($this->pictures),
            'mainpictures' =>  U::getString($this->mainpicture),
            'unitcost' =>  U::getString($this->unitcost)
        ];
    }

    /**
     * @param array|null $vars
     * @return void
     */
    private function toObject(array $vars = null): void
    {
        if (!empty($vars)) {
            $this->id = U::arrayExistInt($vars, [parent::ID], null);
            $this->token =U::arrayExistString($vars, [parent::TOKEN], null);
            $this->reference = U::arrayExistString($vars, [parent::REFERENCE], null);
            $this->nickname = U::arrayExistString($vars, [parent::NICKNAME], null);
            $this->longname = U::arrayExistString($vars, [parent::LONGNAME], null);
            $this->family = Family::_get($this->family->getId(),  U::arrayExistInt($vars, [parent::FAMILY], null));
            $this->instock = U::arrayExistString($vars, [parent::INSTOCK], null) == 1;;
            $this->minorder = U::arrayExistInt($vars, [parent::MINORDER], null);
            $this->maxorder = U::arrayExistInt($vars, [parent::MAXORDER], null);
            $this->features = U::arrayExistString($vars, [parent::FEATURES], null);
            $this->pictures = U::arrayExistString($vars, [parent::PICTURES], null);
            $this->mainpicture = U::arrayExistString($vars, [parent::MAINPICTURES], null);
            $this->unitcost = U::arrayExistInt($vars, [parent::UNITCOST], null);
        }
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ PUBLIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        return (new parent($this))->save();
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return (new parent($this))->delete();
    }

    /**
     * @return array
     */
    public function tabularize(): array
    {
        return $this->toArray();
    }

    /**
     * @param array|null $vars
     * @return void
     */
    public function objectify(?array $vars = null)
    {
        $this->toObject($vars);
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function equal(Product $product)
    {
        return $this->id == $product->id
            && $this->token == $product->token;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode(get_object_vars($this));
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ STATIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param int $method
     * @param $value
     * @return Contact
     */
    public static function _get(int $method, $value = null): Contact
    {
        return parent::_search($method, $value);
    }
}