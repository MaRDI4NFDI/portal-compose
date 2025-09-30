CREATE DATABASE IF NOT EXISTS `wikidata-importer`;
GRANT ALL ON `wikidata-importer`.* TO sqluser@'%';
CREATE TABLE `wikidata-importer`.items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wikidata_id INT NOT NULL,
    local_id INT NOT NULL,
    has_all_claims BOOLEAN NOT NULL,
    INDEX (wikidata_id),
    INDEX (local_id)
);
CREATE TABLE `wikidata-importer`.properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wikidata_id INT NOT NULL,
    local_id INT NOT NULL,
    has_all_claims BOOLEAN NOT NULL,
    INDEX (wikidata_id),
    INDEX (local_id)
);