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
use RedBeanPHP\OODBBean;

class OrderStatusDBA
{
    protected const DATA = 'orderstatus';

    protected const ID = 'id';
    protected const NAME = 'name';
    protected const DESCRIPTION = 'description';
    protected const COLOR = 'color';

    /**
     * @var OrderStatus|null
     */
    private $business;

    protected function __construct(?OrderStatus $business = null)
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
        if (!is_null($bean) && OrderStatus::instanceOf($this->business)) {
            $bean->{static::NAME} = U::getString($this->business->getName());
            $bean->{static::DESCRIPTION} = U::getString($this->business->getDescription());
            $bean->{static::COLOR} = U::getString($this->business->getColor());
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
                    $business = new OrderStatus();
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
    private function findAll() {
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
        if (!OrderStatus::instanceOf($this->business))
            throw new Exception(WaError::_throw('blank_business'));

        if (is_null(U::getString($this->business->getName())))
            throw new Exception(WaError::_throw('name_required'));

        if (is_null(U::getString($this->business->getDescription())))
            throw new Exception(WaError::_throw('description_required'));

        if (is_null(U::getString($this->business->getColor())))
            throw new Exception(WaError::_throw('color_required'));

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
        if (OrderStatus::instanceOf($this->business)) {
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
            if (OrderStatus::instanceOf($this->business)) {
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
     * @return OrderStatus
     */
    protected static function _search(int $id = null): OrderStatus
    {
        $dba = new OrderStatusDBA(new OrderStatus());
        if (!is_null($id))
            $dba->export($dba->find((int) $id));

        return $dba->business;
    }

    /**
     * @return OrderStatus[]
     */
    protected static function _getAll():array {
        $dba = new OrderStatusDBA(new OrderStatus());
        return $dba->exportAll($dba->findAll());
    }


}