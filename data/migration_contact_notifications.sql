-- Notifications des messages de contact
-- À exécuter une seule fois sur une base déjà créée avec maison_rosalie_v1.sql
-- (une nouvelle installation contient déjà la colonne).

ALTER TABLE `maison_rosalie`.`contact_messages`
  ADD COLUMN `is_read` TINYINT(1) NOT NULL DEFAULT 0 AFTER `message`,
  ADD INDEX `idx_contact_messages_is_read` (`is_read` ASC);
