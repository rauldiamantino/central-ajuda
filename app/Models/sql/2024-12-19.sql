-- coluna código
ALTER TABLE `artigos` ADD COLUMN `codigo` BIGINT NOT NULL AFTER `id`;

-- Atualiza registros atuais
SET @empresa_id := NULL;
SET @codigo := 1000;

UPDATE artigos
JOIN (
    SELECT id, empresa_id,
           @codigo := IF(@empresa_id = empresa_id, @codigo + 1, 1001) AS novo_codigo,
           @empresa_id := empresa_id
    FROM artigos
    ORDER BY empresa_id, id
) AS subquery
ON artigos.id = subquery.id
SET artigos.codigo = subquery.novo_codigo;

-- Índice único
ALTER TABLE `artigos` ADD UNIQUE KEY `empresa_codigo_unique` (`empresa_id`, `codigo`);