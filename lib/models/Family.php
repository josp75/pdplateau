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

class Family extends FamilyDBA
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var boolean
     */
    private $active;


    public function __construct(?string $name = null, ?string $description = null, ?bool $active = true)
    {
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
        parent::__construct($this);
    }

    /**
     * @param $object
     * @return bool
     */
    public static function instanceOf($object) : bool
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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
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


    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~  LINKING METHODS  -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return array
     */
    private function toArray(): array
    {
        return [
            parent::NAME => U::getString($this->name),
            parent::DESCRIPTION => U::getString($this->description),
            parent::TOKEN => $this->token,
            parent::ACTIVE => $this->active,
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
            $this->name = U::arrayExistString($vars, [parent::NAME]);
            $this->description = U::arrayExistString($vars, [parent::DESCRIPTION]);
            $this->active = U::arrayExistInt($vars, [parent::ACTIVE]) == 1;
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


    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ STATIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param int|null $id
     * @param string|null $token
     * @return
     */
    public static function _get(int $id = null, string $token = null): Family
    {
        return parent::_search($id, $token);
    }

    /**
     * @return Family[]
     */
    public static function _getAll(): array
    {
        return parent::_getAll();
    }

    /**
     * @param Family[]|null $business
     * @return array
     */
    public static function _tabularize(array $business = null): array
    {
        $result = [];
        if (!empty($business)) {
            foreach ($business as $item) {
                if (self::instanceOf($item))
                    $result[] = $item->tabularize();
            }
        }
        return $result;
    }



}