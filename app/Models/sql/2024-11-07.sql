-- adiciona coluna favicon
ALTER TABLE `empresas` ADD COLUMN `favicon` VARCHAR(255) DEFAULT NULL AFTER `logo`;