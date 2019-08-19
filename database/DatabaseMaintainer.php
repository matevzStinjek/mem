<?php

function truncateAndUpgradeDatabase($code) {
    // exec $code
}

$code = "";

$code .= "
    CREATE TABLE users (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) COLLATE utf8_unicode_ci,
        email VARCHAR(255) COLLATE utf8_unicode_ci,
        passwordHash CHAR(128) COLLATE utf8_unicode_ci,
        roles TEXT COLLATE utf8_unicode_ci,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (id)
    );
"; // TODO: change roles TEXT to something not as hacky

$code .= "INSERT INTO users VALUES(0, 'admin', 'stinjek@gmail.com', '92f39f7f2a869838cd5085e6f17fc82109bcf98cd62a47cbc379e38de80bbc0213a23cee6e4a13de6caae0add8a390272d6f0883c274320b1ff60dbcfc6dd750', 'ADMIN', NOW());";

$code .= "
    CREATE TABLE userGroups (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) COLLATE utf8_unicode_ci,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (id)
    );
";

$code .= "INSERT INTO userGroups VALUES(0, 'tuni', NOW());";

$code .= "
    CREATE TABLE userGroupsMemberships (
        userId INT UNSIGNED NOT NULL,
        userGroupId INT UNSIGNED NOT NULL,
        PRIMARY KEY (userId,userGroupId),
        KEY userGroupsMemberships_userGroupId_k (userGroupId),
        CONSTRAINT userGroupsMemberships_userId_fk FOREIGN KEY (userId) REFERENCES users (id),
        CONSTRAINT userGroupsMemberships_userGroupId_fk FOREIGN KEY (userGroupId) REFERENCES userGroups (id)
    );
";

$code .= "INSERT INTO userGroupsMemberships VALUES(1,1);";

$code .= "
    CREATE TABLE folders (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) COLLATE utf8_unicode_ci,
        creatorId INT UNSIGNED NOT NULL,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (id),
        CONSTRAINT folders_creatorId_fk FOREIGN KEY (creatorId) REFERENCES users (id)
    );
";

$code .= "INSERT INTO folders VALUES(0, 'kuba', 1, NOW());";
