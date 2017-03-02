CREATE TABLE competitions
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    current TINYINT DEFAULT 0 NOT NULL,
    leftSideAlliance CHAR(4)
);
CREATE TABLE devices
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255)
);
CREATE TABLE knex_migrations
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    batch INT,
    migration_time TIMESTAMP
);
CREATE TABLE knex_migrations_lock
(
    is_locked INT
);
CREATE TABLE matchautos
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    crossedLine TINYINT NOT NULL
);
CREATE TABLE matchballfeeds
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    delta INT NOT NULL,
    location CHAR(16) NOT NULL
);
CREATE TABLE matchballfeeds_preprocess
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    `before` DECIMAL(8,2) NOT NULL,
    after DECIMAL(8,2) NOT NULL,
    location CHAR(16) NOT NULL
);
CREATE TABLE matchclimbs
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    touchpad TINYINT NOT NULL,
    duration DECIMAL(8,2) NOT NULL,
    location CHAR(6) NOT NULL
);
CREATE TABLE matches
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    matchNumber INT NOT NULL,
    compID INT UNSIGNED NOT NULL,
    lastUpdated INT UNSIGNED,
    lastExported INT UNSIGNED
);
CREATE TABLE matchgearfeeds
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    result TINYINT NOT NULL,
    method CHAR(8) NOT NULL
);
CREATE TABLE matchgears
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    location CHAR(6) NOT NULL,
    result TINYINT NOT NULL
);
CREATE TABLE matchratings
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    agility INT NOT NULL,
    shootAccuracy INT NOT NULL,
    shootSpeed INT NOT NULL,
    gearFeedAccuracy INT NOT NULL,
    gearFeedSpeed INT NOT NULL,
    ballFeedAccuracy INT NOT NULL,
    ballFeedSpeed INT NOT NULL,
    gearSpeed INT NOT NULL,
    driverSkill INT NOT NULL,
    defense INT NOT NULL
);
CREATE TABLE matchshoots
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    coordX DECIMAL(8,2) NOT NULL,
    coordY DECIMAL(8,2) NOT NULL,
    highLow TINYINT NOT NULL,
    scored INT NOT NULL,
    missed INT NOT NULL
);
CREATE TABLE matchshoots_preprocess
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamMatchID INT UNSIGNED NOT NULL,
    orderID INT UNSIGNED NOT NULL,
    mode CHAR(5) NOT NULL,
    coordX DECIMAL(8,2) NOT NULL,
    coordY DECIMAL(8,2) NOT NULL,
    scored INT,
    missed INT,
    `before` DECIMAL(8,2),
    after DECIMAL(8,2),
    accuracy DECIMAL(8,2)
);
CREATE TABLE scouters
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255)
);
CREATE TABLE teammatches
(
    id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    matchID INT UNSIGNED NOT NULL,
    side CHAR(4) NOT NULL,
    position INT UNSIGNED NOT NULL,
    teamNumber INT UNSIGNED NOT NULL,
    deviceID INT UNSIGNED NOT NULL,
    collectionStarted TINYINT DEFAULT 0 NOT NULL,
    collectionEnded TINYINT DEFAULT 0 NOT NULL,
    scouterID INT UNSIGNED NOT NULL,
    postprocessed TINYINT DEFAULT 0 NOT NULL,
    ready TINYINT DEFAULT 0 NOT NULL
);
CREATE TABLE teampit
(
    teamNumber INT UNSIGNED PRIMARY KEY NOT NULL,
    climbs TINYINT NOT NULL,
    gears TINYINT NOT NULL,
    lowShoots TINYINT NOT NULL,
    highShoots TINYINT NOT NULL,
    groundFeedsBalls TINYINT NOT NULL,
    hoppers TINYINT NOT NULL,
    loadingLanesBalls TINYINT NOT NULL,
    groundFeedsGears TINYINT NOT NULL,
    loadingLanesGears TINYINT NOT NULL
);
CREATE TABLE teampitdrivetrains
(
    teamNumber INT UNSIGNED PRIMARY KEY NOT NULL,
    type VARCHAR(255) NOT NULL
);
CREATE TABLE teams
(
    teamNumber INT UNSIGNED PRIMARY KEY NOT NULL,
    name VARCHAR(255) NOT NULL
);
CREATE UNIQUE INDEX devices_key_unique ON devices (`key`);
ALTER TABLE matchautos ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchautos_teammatchid_unique ON matchautos (teamMatchID);
ALTER TABLE matchballfeeds ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchballfeeds_teammatchid_orderid_unique ON matchballfeeds (teamMatchID, orderID);
ALTER TABLE matchballfeeds_preprocess ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchballfeeds_preprocess_teammatchid_orderid_unique ON matchballfeeds_preprocess (teamMatchID, orderID);
ALTER TABLE matchclimbs ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchclimbs_teammatchid_orderid_unique ON matchclimbs (teamMatchID, orderID);
ALTER TABLE matches ADD FOREIGN KEY (compID) REFERENCES competitions (id);
CREATE INDEX matches_compid_foreign ON matches (compID);
ALTER TABLE matchgearfeeds ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchgearfeeds_teammatchid_orderid_unique ON matchgearfeeds (teamMatchID, orderID);
ALTER TABLE matchgears ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchgears_teammatchid_orderid_unique ON matchgears (teamMatchID, orderID);
ALTER TABLE matchratings ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchratings_teammatchid_unique ON matchratings (teamMatchID);
ALTER TABLE matchshoots ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchshoots_teammatchid_orderid_unique ON matchshoots (teamMatchID, orderID);
ALTER TABLE matchshoots_preprocess ADD FOREIGN KEY (teamMatchID) REFERENCES teammatches (id);
CREATE UNIQUE INDEX matchshoots_preprocess_teammatchid_orderid_unique ON matchshoots_preprocess (teamMatchID, orderID);
CREATE UNIQUE INDEX scouters_name_unique ON scouters (name);
ALTER TABLE teammatches ADD FOREIGN KEY (scouterID) REFERENCES scouters (id);
ALTER TABLE teammatches ADD FOREIGN KEY (deviceID) REFERENCES devices (id);
ALTER TABLE teammatches ADD FOREIGN KEY (matchID) REFERENCES matches (id);
ALTER TABLE teammatches ADD FOREIGN KEY (teamNumber) REFERENCES teams (teamNumber);
CREATE UNIQUE INDEX teammatches_teamnumber_matchid_unique ON teammatches (teamNumber, matchID);
CREATE INDEX teammatches_deviceid_foreign ON teammatches (deviceID);
CREATE INDEX teammatches_matchid_foreign ON teammatches (matchID);
CREATE INDEX teammatches_scouterid_foreign ON teammatches (scouterID);
ALTER TABLE teampit ADD FOREIGN KEY (teamNumber) REFERENCES teams (teamNumber);
ALTER TABLE teampitdrivetrains ADD FOREIGN KEY (teamNumber) REFERENCES teams (teamNumber);
