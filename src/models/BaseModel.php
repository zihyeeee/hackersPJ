<?php

namespace src\models;

use src\core\DB;

class BaseModel
{
    protected $db = null;
    protected $db_slave = null;

    public function __construct() {
        if(IS_DEV) {
            $this->db = new DB('hacademia');
            $this->db_slave = new DB('hacademia');
        } else {
            $this->db = new DB('master');
            $this->db_slave = new DB('slave');
        }
    }
}
