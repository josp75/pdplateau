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

class Store extends StoreDBA
{
    const SEARCHID = 0;
    const SEARCHTOKEN = 1;
    const SEARCHNAME= 2;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var boolean
     */
    private $default;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Contact
     */
    private $owner;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $creadate;


    public function __construct(?string $name = null, string $description = null, Contact $owner =null, Address $address = null, bool $default = null){
        $this->name = $name;
        $this->description = $description;
        $this->owner = $owner;
        $this->address = $address;
        $this->default = $default;
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
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isDefault(): ?bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault(?bool $default): void
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Contact
     */
    public function getOwner(): ?Contact
    {
        return $this->owner;
    }

    /**
     * @param Contact|null $owner
     */
    public function setOwner(?Contact $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getCreadate(): string
    {
        return $this->creadate;
    }

    /**
     * @param string $creadate
     */
    public function setCreadate(string $creadate): void
    {
        $this->creadate = $creadate;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
            'code' => $this->code,
            'name' => U::getString($this->name),
            'description' => U::getString($this->description),
            'owner' => Contact::instanceOf($this->owner) ? $this->owner->tabularize() : null
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
            $this->token = U::arrayExistString($vars, [parent::TOKEN]);
            $this->code = U::arrayExistString($vars, [parent::CODE]);
            $this->name = U::arrayExistString($vars, [parent::NAME]);
            $this->description = U::arrayExistString($vars, [parent::DESCRIPTION]);
            $this->owner = Contact::_get(Address::SEARCHID,  U::arrayExistInt($vars, [parent::OWNER], null));
            $this->address = Address::_get(Address::SEARCHID, U::arrayExistInt($vars, [parent::ADDRESS], null));
            $this->default = U::arrayExistInt($vars, [parent::DEFAULT]) == 1;
            $this->active = U::arrayExistInt($vars, [parent::ACTIVE]) == 1;
            $this->creadate = U::arrayExistString($vars, [parent::CREADATE]);
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
     * @param Store $store
     * @return bool
     */
    public function equal(Store $store)
    {
        return $this->id == $store->id
            && $this->token == $store->token
            && $this->owner->equal($store->owner)
            && $this->address->equal($store->address);
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