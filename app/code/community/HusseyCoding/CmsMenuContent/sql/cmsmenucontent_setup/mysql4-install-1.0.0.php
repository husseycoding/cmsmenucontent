<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('cms/page')}
        ADD `is_menu_page` TINYINT NOT NULL DEFAULT '0',
        ADD `menu_items` TEXT NULL DEFAULT NULL,
        ADD `link_colour` TINYTEXT NULL DEFAULT NULL,
        ADD `hover_colour` TINYTEXT NULL DEFAULT NULL,
        ADD `active_colour` TINYTEXT NULL DEFAULT NULL,
        ADD `ahover_colour` TINYTEXT NULL DEFAULT NULL,
        ADD `block_order` TEXT NULL DEFAULT NULL
");

$installer->run("
    ALTER TABLE {$this->getTable('cms/block')}
        ADD `use_in_menu_page` TINYINT NOT NULL DEFAULT '0',
        ADD `link_text` TEXT NULL DEFAULT NULL
");

$installer->endSetup();