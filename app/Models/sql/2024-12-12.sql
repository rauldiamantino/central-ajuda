--- index empresa_id email
ALTER TABLE `usuarios`
DROP INDEX `email`,
ADD UNIQUE INDEX `unico_empresa_email` (`empresa_id`,`email`) USING BTREE;