<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_112 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("INSERT INTO `tbl_languages` (`code`, `name`, `icon`, `active`) VALUES
        ('cs', 'czech', 'cz', 1),
        ('da', 'danish', 'dk', 1),
        ('el', 'greek', 'cy', 1),
        ('es', 'spanish', 'ar', 1),
        ('gu', 'gujarati', 'in', 1),
        ('hi', 'hindi', 'in', 1),
        ('id', 'indonesian', 'id', 1),
        ('ja', 'japanese', 'jp', 1),
        ('no', 'norwegian', 'no', 1),
        ('pl', 'polish', 'pl', 1),
        ('pt', 'portuguese', 'br', 1),
        ('ro', 'romanian', 'md', 1),
        ('ru', 'russian', 'ru', 1),
        ('zh', 'chinese', 'cn', 1)");

        $this->db->query("ALTER TABLE `tbl_tickets` ADD (project_id int(11) NULL DEFAULT '0')");
        $this->db->query("ALTER TABLE `tbl_task` ADD (hourly_rate decimal(18,2) NULL DEFAULT '0.00')");
        $this->db->query("CREATE TABLE `tbl_migrations` (`version` bigint(20) NOT NULL);");
        $this->db->query("INSERT INTO `tbl_migrations` (`version`) VALUES(112);");
    }
}
