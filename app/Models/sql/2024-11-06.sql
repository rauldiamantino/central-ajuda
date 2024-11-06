-- adiciona coluna protocolo
ALTER TABLE `empresas` ADD COLUMN `protocolo` VARCHAR(255) AFTER `plano_nome`;