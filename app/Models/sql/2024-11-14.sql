-- adiciona coluna assinatura_status
ALTER TABLE `empresas` ADD COLUMN `assinatura_status` tinyint DEFAULT '0' AFTER `assinatura_id_asaas`;