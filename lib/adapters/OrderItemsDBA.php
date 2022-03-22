<?php

class OrderItemsDBA
{
    protected const DATA = 'orderitems';

    protected const ID = 'id';
    protected const PRODUCT = 'product';
    protected const QUANTITY = 'quantity';
    protected const UNICOST = 'unicost';
    protected const AMOUNT = 'amount';
    protected const CREADATE = 'creadate';

    /**
     * @var OrderItems|null
     */
    private $business;

    protected function __construct(?OrderItems $business = null)
    {
        $this->business = $business;
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~  LINKING METHODS  -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param OODBBean|null $bean
     * @return OODBBean|null
     */
    private function toBean(OODBBean $bean = null): ?OODBBean
    {
        if (!is_null($bean) && OrderItems::instanceOf($this->business)) {
            $bean->{static::PRODUCT} = $this->business->getProduct()->getId();
            $bean->{static::QUANTITY} =$this->business->getQuantity();
            $bean->{static::UNICOST} = $this->business->getProduct()->getUnitcost();
            $bean->{static::AMOUNT} = $this->business->getAmount();
            $bean->{static::CREADATE} =  $this->business->getDateOfCreate();
            return $bean;
        }
        return null;
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~   EXPORT METHODS  -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param OODBBean|null $bean
     * @return void
     */
    private function export(OODBBean $bean = null): void
    {
        if ($bean instanceof OODBBean)
            $this->business->objectify($bean->export());
    }

    /**
     * @param array|null $beans
     * @return OrderStatus[]
     */
    private function exportAll(array $beans = null): array
    {
        $businesses = [];
        if (!empty($beans)) {
            foreach ($beans as $bean) {
                if ($bean instanceof OODBBean) {
                    $business = new OrderItems();
                    $business->objectify($bean->export());
                    $businesses[] = $business;
                }
            }
        }
        return $businesses;
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ -~ DATA LINK METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param int $id
     * @return OODBBean|NULL
     */
    private function find(int $id)
    {
        return R::findOne(static::DATA, " `" . static::ID . "` = ? ", [$id]);
    }



    /**
     * @return array
     */
    private function findAll(): array
    {
        return R::findAll(static::DATA);
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ MIDDLE LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return bool
     * @throws Exception
     */
    private function passed(): bool
    {
        if (!Family::instanceOf($this->business))
            throw new Exception(WaError::_throw('blank_business'));

        if (is_null(U::getString($this->business->getLastName())))
            throw new Exception(WaError::_throw('name_required'));

        if (is_null($this->business->isMaleGender()))
            throw new Exception(WaError::_throw('gender_required'));

        if (is_null(U::getString($this->business->getAge())))
            throw new Exception(WaError::_throw('age_required'));

        if (is_null(U::getString($this->business->getDateOfCreate())))
            throw new Exception(WaError::_throw('createdDate_required'));

        return true;
    }

    /**
     * @return int
     * @throws Exception
     */
    private function create(): int
    {
        if ($this->passed()) {
            $lastID = R::store($this->toBean(R::dispense(static::DATA)));

            if (is_int($lastID) && $lastID > 0)
                return (int)$lastID;
        }
        return -1;
    }

    /**
     * @return int
     * @throws Exception
     */
    private function update(): int
    {
        if ($this->passed()) {
            $lastID = R::store($this->toBean(R::loadForUpdate(self::DATA, $this->business->getId())));

            if (is_int($lastID) && $lastID > 0)
                return (int)$lastID;
        }
        return -1;
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ PUBLIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @return bool
     * @throws Exception
     */
    protected function save(): bool
    {
        if (Family::instanceOf($this->business)) {
            $lastID = ((int)$this->business->getId() > 0) ? $this->update() : $this->create();
            if ($lastID > 0) {
                $this->export($this->find((int)$lastID));
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function delete(): bool
    {
        try {
            if (Family::instanceOf($this->business)) {
                R::trash($this->find($this->business->getId()));
                return true;
            }
        } catch (Exception $e) {
            Watcher::tracker($e, self::class, __FUNCTION__);
        }
        return false;
    }

    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #
    # -~ STATIC LOGIC METHODS -~ -~ #
    # -~ -~ -~ -~ -~ -~ -~ -~ -~ -~ #

    /**
     * @param int|null $id
     * @return OrderItems
     */
    protected static function _search(int $method = 0, ?string $value = null): OrderItems
    {
        $dba = new OrderItemsDBA(new OrderItems());

        $dba->export($dba->find((int)$value));

        return $dba->business;
    }

    /**
     * @return OrderItems[]
     */
    protected static function _getAll():array {
        $dba = new OrderItemsDBA(new OrderItems());
        return $dba->exportAll($dba->findAll());
    }


}