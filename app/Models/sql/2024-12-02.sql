ALTER TABLE `empresas` ADD COLUMN `espaco` INT UNSIGNED DEFAULT 0 AFTER `assinatura_ciclo`;

-- assinatura
CREATE TABLE `assinaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gratis_prazo` datetime DEFAULT NULL,
  `asaas_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `status` tinyint DEFAULT 0,
  `valor` decimal(10,2) DEFAULT '0.00',
  `ciclo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `espaco` int unsigned DEFAULT 0,
  `empresa_id` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id_asaas_id_empresa_id` (`id`, `asaas_id`,`empresa_id`),
  KEY `assinaturas_empresa_id_idx` (`empresa_id`)
)

ALTER TABLE `central-ajuda-teste`.`empresas`
DROP COLUMN `gratis_prazo`,
DROP COLUMN `assinatura_id_asaas`,
DROP COLUMN `assinatura_status`,
DROP COLUMN `assinatura_valor`,
DROP COLUMN `assinatura_ciclo`,
DROP COLUMN `espaco`;