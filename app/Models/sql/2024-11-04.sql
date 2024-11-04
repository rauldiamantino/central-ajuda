-- adiciona colunas plano_nome e plano_valor
ALTER TABLE `empresas`
ADD COLUMN `plano_nome` VARCHAR(50) NOT NULL AFTER `assinatura_id`,
ADD COLUMN `plano_valor` DECIMAL(10, 2) NOT NULL AFTER `plano_nome`;