<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_111 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("CREATE TABLE `tbl_sessions` (`id` varchar(40) NOT NULL,`ip_address` varchar(45) NOT NULL,`timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',`data` blob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->db->query("ALTER TABLE `tbl_sessions` ADD PRIMARY KEY (`id`), ADD KEY `ci_sessions_timestamp` (`timestamp`)");
    }
}
