<?php

function truncateAndUpgradeDatabase() {
    $code = "
        CREATE TABLE photos (
            id char(8) CHARACTER SET ascii NOT NULL,
            title varchar(64) COLLATE utf8_unicode_ci,
            image varchar(150) COLLATE utf8_unicode_ci,
            slug varchar(100) COLLATE utf8_unicode_ci,
            PRIMARY KEY (`id`)
        );
    ";
}
