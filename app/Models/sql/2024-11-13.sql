-- adiciona coluna assinatura_id_asaas
ALTER TABLE `empresas` ADD COLUMN `assinatura_id_asaas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' AFTER `assinatura_id`;

-- remove stripe
ALTER TABLE empresas
DROP COLUMN sessao_stripe_id,
DROP COLUMN assinatura_id,
DROP COLUMN plano_nome,
DROP COLUMN protocolo,
DROP COLUMN plano_valor;
