CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8_unicode_ci,
    email VARCHAR(255) COLLATE utf8_unicode_ci,
    passwordHash CHAR(64) COLLATE utf8_unicode_ci,
    salt CHAR(16) COLLATE utf8_unicode_ci,
    roles LONGBLOB COLLATE utf8_unicode_ci,
    creationTimestamp DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sessions (
    id varchar(64) COLLATE utf8_unicode_ci NOT NULL,
    userId INT UNSIGNED DEFAULT NULL,
    creationTimestamp datetime NOT NULL,
    lastActivityTimestamp datetime DEFAULT NULL,
    httpUserAgent text COLLATE utf8_unicode_ci DEFAULT NULL,
    persistConfirmed tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY sessions_userId_k (userId),
    KEY sessions_persistConfirmed_lastActivityTimestamp_k (persistConfirmed, lastActivityTimestamp),
    CONSTRAINT sessions_userId_fk FOREIGN KEY (userId) REFERENCES users (id)
)

INSERT INTO users VALUES(0, 'admin', 'admin@mem.com', '259926f400eb547cd6b5dea51e50291f2e1d8ed8df452b7c9646ef9a8862d874', '$salt', 'ADMIN', NOW());

CREATE TABLE userGroups (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8_unicode_ci,
    creationTimestamp DATETIME NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO userGroups VALUES(0, 'tuni', NOW());

CREATE TABLE userGroupMemberships (
    userId INT UNSIGNED NOT NULL,
    userGroupId INT UNSIGNED NOT NULL,
    PRIMARY KEY (userId,userGroupId),
    KEY userGroupMemberships_userGroupId_k (userGroupId),
    CONSTRAINT userGroupMemberships_userId_fk FOREIGN KEY (userId) REFERENCES users (id),
    CONSTRAINT userGroupMemberships_userGroupId_fk FOREIGN KEY (userGroupId) REFERENCES userGroups (id)
);

INSERT INTO userGroupMemberships VALUES(1,1);

CREATE TABLE folders (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8_unicode_ci,
    creatorId INT UNSIGNED NOT NULL,
    creationTimestamp DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT folders_creatorId_fk FOREIGN KEY (creatorId) REFERENCES users (id)
);

INSERT INTO folders VALUES(0, 'kuba', 1, NOW());

CREATE TABLE folderMemberships (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    userId INT UNSIGNED DEFAULT NULL,
    userGroupId INT UNSIGNED DEFAULT NULL,
    folderId INT UNSIGNED NOT NULL,
    readOnly tinyint(1) NOT NULL DEFAULT '0',
    creationTimestamp DATETIME NOT NULL,
    lastModificationTimestamp DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY folderMemberships_userId_userGroupId_folderId_uk (userId, userGroupId, folderId),
    KEY folderMemberships_folderId_k (folderId),
    KEY folderMemberships_userGrouupId_k (userGroupId),
    KEY folderMemberships_userId_k (userId),
    CONSTRAINT folderMemberships_userId_fk FOREIGN KEY (userId) REFERENCES users (id),
    CONSTRAINT folderMemberships_userGroupId_fk FOREIGN KEY (userGroupId) REFERENCES userGroups (id),
    CONSTRAINT folderMemberships_folderId_fk FOREIGN KEY (folderId) REFERENCES folders (id)
);

CREATE VIEW v_userFolderAS
    SELECT f.*, u* FROM folderMemberships as fm, users as u, folders as f WHERE fm.folderId = f.id AND fm.userId = u.id
UNION
    SELECT f.*, u* FROM folderMemberships as fm, users as u, userGroups as ug, userGroupsMemberships as ugm, folders as f WHERE fm.folderId = f.id AND fm.userGroupId = ug.id AND ugm.userGroupId = ug.id AND ugm.userId = u.id AND u.id = 1;

CREATE TABLE folderContent (
    blobHash char(64) COLLATE utf8_unicode_ci NOT NULL,
    folderId INT UNSIGNED NOT NULL,
    uploaderId INT UNSIGNED NOT NULL,
    creationTimestamp DATETIME NOT NULL,
    PRIMARY KEY (blobHash),
    CONSTRAINT folderContent_folderId_fk FOREIGN KEY (folderId) REFERENCES folders (id),
    CONSTRAINT folderContent_uploaderId_fk FOREIGN KEY (uploaderId) REFERENCES users (id)
);

-- blob hash
INSERT INTO folderContent VALUES('199f25fd69940560d438a6d7d3ace16e3cb4eab9cf2ad6ff069ccdc0585bb8b3', 1, 1, NOW());
