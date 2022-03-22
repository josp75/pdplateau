<?php

class ProductDBA
{
    protected const DATA = 'store';

    protected const ID = 'id';
    protected const TOKEN = 'token';
    protected const REFERENCE = 'reference';
    protected const NICKNAME = 'nickname';
    protected const LONGNAME = 'longname';
    protected const FAMILY = 'family';
    protected const INSTOCK = 'instock';
    protected const MINORDER = 'minorder';
    protected const MAXORDER = 'maxorder';
    protected const FEATURES = 'features';
    protected const PICTURES = 'pictures';
    protected const MAINPICTURES = 'mainpicture';
    protected const UNITCOST = 'unitcost';

    /**
     * @var Product|null
     */
    private $business;

    protected function __construct(?Product $business = null)
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
            $bean->{static::REFERENCE} = U::getString($this->business->getReference());
            $bean->{static::NICKNAME} = U::getString($this->business->getNickname());
            $bean->{static::LONGNAME} = $this->business->getLongname();
            $bean->{static::FAMILY} = $this->business->getFamily()->getId();
            $bean->{static::INSTOCK} =  $this->business->isInstock();
            $bean->{static::MINORDER} =  $this->business->getMinorder();
            $bean->{static::MAXORDER} =  $this->business->getMaxorder();
            $bean->{static::FEATURES} = U::getString($this->business->getFeatures());
            $bean->{static::PICTURES} =  U::getString( $this->business->getPictures());
            $bean->{static::MAINPICTURES} =  U::getString( $this->business->getPictures());
            $bean->{static::UNITCOST} = $this->business->getUnitcost();
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
        if (!Product::instanceOf($this->business))
            throw new Exception(WaError::_throw('blank_business'));

        if (is_null(U::getString($this->business->getNickname())))
            throw new Exception(WaError::_throw('Nickname_required'));

        if (is_null($this->business->getFamily()))
            throw new Exception(WaError::_throw('family_required'));

        if (is_null($this->business->getMainpicture()))
            throw new Exception(WaError::_throw('mainpicture_required'));

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
     * @param int $method
     * @param string|null $value
     * @return Product
     */
    protected static function _search(int $method = 0, ?string $value = null): Product
    {
        $dba = new ProductDBA(new Product());
        switch ($method){
            case Product::SEARCHID:
                $dba->export($dba->find((int) $value));
                break;
            case Store::SEARCHTOKEN:
                $dba->export($dba->findByToken($value));
                break;

        }
        return $dba->business;
    }

    /**
     * @return Product[]
     */
    protected static function _getAll():array {
        $dba = new ProductDBA(new Product());
        return $dba->exportAll($dba->findAll());
    }


}