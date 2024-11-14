-- adiciona coluna assinatura_status
ALTER TABLE `empresas` ADD COLUMN `assinatura_status` tinyint DEFAULT '0' AFTER `assinatura_id_asaas`;

-- adiciona colunas assinatura_valor e assinatura_ciclo
ALTER TABLE `empresas` ADD COLUMN `assinatura_valor` DECIMAL(10, 2) DEFAULT '0.00' AFTER `assinatura_status`;
ALTER TABLE `empresas` ADD COLUMN `assinatura_ciclo` VARCHAR(50) DEFAULT '' AFTER `assinatura_valor`;

-- Coluna gratis prazo
ALTER TABLE `empresas` ADD COLUMN `gratis_prazo` datetime AFTER `favicon`;