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
        passwordHash CHAR(64) COLLATE utf8_unicode_ci,
        salt CHAR(16) COLLATE utf8_unicode_ci,
        roles set COLLATE utf8_unicode_ci,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (id)
    );
";

$code .= "INSERT INTO users VALUES(0, 'admin', 'admin@mem.com', '259926f400eb547cd6b5dea51e50291f2e1d8ed8df452b7c9646ef9a8862d874', '$salt', 'ADMIN', NOW());";

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
    CREATE TABLE userGroupMemberships (
        userId INT UNSIGNED NOT NULL,
        userGroupId INT UNSIGNED NOT NULL,
        PRIMARY KEY (userId,userGroupId),
        KEY userGroupMemberships_userGroupId_k (userGroupId),
        CONSTRAINT userGroupMemberships_userId_fk FOREIGN KEY (userId) REFERENCES users (id),
        CONSTRAINT userGroupMemberships_userGroupId_fk FOREIGN KEY (userGroupId) REFERENCES userGroups (id)
    );
";

$code .= "INSERT INTO userGroupMemberships VALUES(1,1);";

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

// \\ TODO
$code .= "
    CREATE TABLE folderMemberships (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        userId INT UNSIGNED,
        userGroupId INT UNSIGNED,
        folderId INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        ...
    );
";
// \\ TODO

$code .= "
    CREATE TABLE folderContent (
        blobHash char(64) COLLATE utf8_unicode_ci NOT NULL,
        folderId INT UNSIGNED NOT NULL,
        uploaderId INT UNSIGNED NOT NULL,
        creationTimestamp DATETIME NOT NULL,
        PRIMARY KEY (blobHash),
        CONSTRAINT folderContent_folderId_fk FOREIGN KEY (folderId) REFERENCES folders (id),
        CONSTRAINT folderContent_uploaderId_fk FOREIGN KEY (uploaderId) REFERENCES users (id)
    );
";

$code .= "INSERT INTO folderContent VALUES('199f25fd69940560d438a6d7d3ace16e3cb4eab9cf2ad6ff069ccdc0585bb8b3', 1, 1, NOW());";
