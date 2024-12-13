--- index empresa_id email
ALTER TABLE `central-ajuda-teste`.`usuarios`
DROP INDEX `email`,
ADD UNIQUE INDEX `unico_empresa_email` (`empresa_id`,`email`) USING BTREE;