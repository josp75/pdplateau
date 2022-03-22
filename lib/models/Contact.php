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

class Contact extends ContactDBA
{
    const SEARCHID = 0;
    const SEARCHTOKEN = 1;
    const SEARCHADDR= 2;

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
    private $maleGender;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var int
     */
    private $age;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var boolean
     */
    private $frSpeaker;

    /**
     * @var string
     */
    private $dateOfCreate;

    /**
     * @param bool|null $maleGender
     * @param string|null $firstName
     * @param string|null $lastName
     * @param Address|null $address
     * @param bool|null $frSpeaker
     */
    public function __construct(bool $maleGender = null, ?string $firstName = null, string $lastName = null,
                                Address $address = null, bool $frSpeaker = null)
    {
        $this->maleGender = $maleGender;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->frSpeaker = $frSpeaker;
        parent::__construct($this);
    }

    /**
     * @param $object
     * @return bool
     */
    public static function instanceOf($object)
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
    public function isMaleGender(): ?bool
    {
        return $this->maleGender;
    }

    /**
     * @param bool $maleGender
     */
    public function setMaleGender(?bool $maleGender): void
    {
        $this->maleGender = $maleGender;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
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
    public function isFrSpeaker(): ?bool
    {
        return $this->frSpeaker;
    }

    /**
     * @param bool $frSpeaker
     */
    public function setFrSpeaker(?bool $frSpeaker): void
    {
        $this->frSpeaker = $frSpeaker;
    }

    /**
     * @return string
     */
    public function getDateOfCreate(): string
    {
        return $this->dateOfCreate;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }



    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~  LINKING METHODS  -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return array
     */
    private function toArray()
    {
        return [
            'code' => $this->token,
            'gender' => $this->maleGender ? 'M' : 'F',
            'firstname' => U::getString($this->firstName),
            'lastname' => U::getString($this->lastName),
            'age' => $this->age,
            'address' => Address::instanceOf($this->address) ? $this->address->tabularize() : null,
            'language' => $this->frSpeaker ? 'fr' : 'en'
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
            $this->maleGender = U::arrayExistInt($vars, [parent::GENDER]) == 1;
            $this->firstName = U::arrayExistString($vars, [parent::FIRSTNAME]);
            $this->lastName = U::arrayExistString($vars, [parent::LASTNAME]);
            $this->age = U::arrayExistString($vars, [parent::AGE]);
            $this->address = Address::_get(Address::SEARCHID, U::arrayExistInt($vars, [parent::ADDRESS], null));
            $this->frSpeaker = U::arrayExistInt($vars, [parent::FRPEAKER]) == 1;
            $this->dateOfCreate = U::arrayExistString($vars, [parent::CREADATE]);
        }
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ PUBLIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return bool
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
    public function tabularize()
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
     * @return string
     */
    public function getName()
    {
        return (!is_null(U::getString($this->firstName)) ? U::getString($this->firstName) . ' ':'')
            . Usable::upper($this->lastName);
    }

    /**
     * @return string|null
     */
    public function getCivility()
    {
        return $this->isMaleGender() ? 'mr' : 'ms';
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->isFrSpeaker() ? WaConst::_FR : WaConst::_EN;
    }

    /**
     * @param Contact $contact
     * @return bool
     */
    public function equal(Contact $contact)
    {
        return $this->id == $contact->id
            && $this->token == $contact->token
            && $this->address->equal($contact->address);
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