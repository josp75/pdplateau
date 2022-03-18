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

class OrderStatus extends OrderStatusDBA
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $color;


    /**
     * @param string|null $name
     * @param string|null $description
     * @param string|null $color
     */
    public function __construct(?string $name = null, ?string $description = null, ?string $color = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->color = $color;
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
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
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
            parent::NAME => $this->name,
            parent::DESCRIPTION => U::getString($this->description),
            parent::COLOR => U::getString($this->color)
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
            $this->name = U::arrayExistString($vars, [parent::NAME]);
            $this->description = U::arrayExistString($vars, [parent::DESCRIPTION]);
            $this->color = U::arrayExistString($vars, [parent::COLOR]);
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


    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ STATIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param int|null $id
     * @return OrderStatus
     */
    public static function _get(int $id = null): OrderStatus
    {
        return parent::_search($id);
    }

    /**
     * @return OrderStatus[]
     */
    public static function _getAll(): array
    {
        return parent::_getAll();
    }

    /**
     * @param OrderStatus[]|null $business
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