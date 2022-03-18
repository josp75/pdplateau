<?php

class StoreDBA
{
    protected const DATA = 'store';

    protected const ID = 'id';
    protected const TOKEN = 'token';
    protected const CODE = 'code';
    protected const NAME = 'name';
    protected const DESCRIPTION = 'description';
    protected const OWNER = 'owner';
    protected const ADDRESS = 'address';
    protected const DEFAULT = 'default';
    protected const ACTIVE = 'active';
    protected const CREADATE = 'creadate';

    /**
     * @var Store|null
     */
    private $business;

    protected function __construct(?Store $business = null)
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
        if (!is_null($bean) && Store::instanceOf($this->business)) {
            $bean->{static::NAME} = U::getString($this->business->getName());
            $bean->{static::DESCRIPTION} = U::getString($this->business->getDescription());
            $bean->{static::OWNER} = $this->business->getOwner()->getId();
            $bean->{static::DEFAULT} = $this->business->isDefault();
            $bean->{static::ACTIVE} =  $this->business->isActive();
            $bean->{static::CREADATE} =  $this->business->getCreadate();
            $bean->{static::ADDRESS} =  $this->business->getAddress()->getId();
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
                    $business = new Store();
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
     * @param string $token
     * @return OODBBean|NULL
     */
    private function findByToken(string $token): ?OODBBean
    {
        return R::findOne(static::DATA, " `" . static::TOKEN . "` LIKE '{$token}' ");
    }

    /**
     * @return OODBBean|NULL
     */
    private function findByName(String $name): ?OODBBean
    {
        return R::findOne(static::DATA, " `" . static::ADDRESS . "` LIKE '{$name}'");
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
        if (!Store::instanceOf($this->business))
            throw new Exception(WaError::_throw('blank_business'));

        if (is_null(U::getString($this->business->getName())))
            throw new Exception(WaError::_throw('name_required'));

        if (is_null($this->business->getOwner()))
            throw new Exception(WaError::_throw('owner_required'));

        if (is_null($this->business->getAddress()))
            throw new Exception(WaError::_throw('address_required'));

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
     * @return Store
     */
    protected static function _search(int $method = 0, ?string $value = null): Store
    {
        $dba = new StoreDBA(new Store());
        switch ($method){
            case Store::SEARCHID:
                $dba->export($dba->find((int) $value));
                break;
            case Store::SEARCHTOKEN:
                $dba->export($dba->findByToken($value));
                break;
            case Store::SEARCHNAME:
                $dba->export($dba->findByName($value));

        }
        return $dba->business;
    }

    /**
     * @return Contact[]
     */
    protected static function _getAll():array {
        $dba = new StoreDBA(new Store());
        return $dba->exportAll($dba->findAll());
    }


}