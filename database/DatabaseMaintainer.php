<?php

function truncateAndUpgradeDatabase($code) {
    // exec $code
}

$code = "
    CREATE TABLE photos (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) COLLATE utf8_unicode_ci,
        image VARCHAR(255) COLLATE utf8_unicode_ci,
        slug VARCHAR(255) COLLATE utf8_unicode_ci,
        parentId INT UNSIGNED,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (id)
    );
";
