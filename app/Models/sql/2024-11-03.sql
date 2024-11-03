-- adiciona íncice empresa_id
ALTER TABLE `ajustes`
ADD INDEX `ajustes_empresa_id_idx` (`empresa_id`);

ALTER TABLE `artigos`
ADD INDEX `artigos_empresa_id_idx` (`empresa_id`);

ALTER TABLE `categorias`
ADD INDEX `categorias_empresa_id_idx` (`empresa_id`);

ALTER TABLE `conteudos`
ADD INDEX `conteudos_empresa_id_idx` (`empresa_id`);

ALTER TABLE `usuarios`
ADD INDEX `usuarios_empresa_id_idx` (`empresa_id`);