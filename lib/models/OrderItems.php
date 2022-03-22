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

class OrderItems extends OrderItemsDBA
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var int
     */
    private $unitcost;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $dateOfCreate;

    /**
     * @param Product|null $product
     * @param int|null $quantity
     * @param int $unitcost
     * @param int $amount
     * @param string $dateOfCreate
     */
    public function __construct(?Product $product = null, ?int $quantity =null, int $unitcost = null, int $amount = null , string $dateOfCreate = null)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->unitcost = $unitcost;
        $this->amount = $amount;
        $this->dateOfCreate = $dateOfCreate;
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
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
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
    public function setUnitcost(?int $unitcost): void
    {
        $this->unitcost = $unitcost;
    }

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getDateOfCreate(): ?string
    {
        return $this->dateOfCreate;
    }

    /**
     * @param string|null $dateOfCreate
     */
    public function setDateOfCreate(?string $dateOfCreate): void
    {
        $this->dateOfCreate = $dateOfCreate;
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
            'product' => Product::instanceOf($this->product) ? $this->product->tabularize() : null,
            'quantity' => $this->quantity,
            'unicost' => $this->unitcost,
            'amount' => $this->amount
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
            $this->product = Product::_get(Product::SEARCHID, U::arrayExistInt($vars, [parent::PRODUCT], null));
            $this->quantity = U::arrayExistInt($vars, [parent::QUANTITY]);
            $this->unitcost = U::arrayExistInt($vars, [parent::UNICOST]);
            $this->amount = U::arrayExistInt($vars, [parent::AMOUNT]);
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
     * @return OrderItems
     */
    public static function _get(int $method, $value = null): OrderItems
    {
        return parent::_search($method, $value);
    }
}