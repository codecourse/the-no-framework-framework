<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;

class ExistsRule
{
    protected $db;

    public function __construct(EntityManager $db)
    {
        $this->db = $db;
    }

    public function validate($field, $value, $params, $fields)
    {
        $result = $this->db->getRepository($params[0])
            ->findOneBy([
                $field => $value
            ]);

        return $result === null;
    }
}
