-- meta categorias
ALTER TABLE `categorias`
ADD COLUMN `meta_titulo` VARCHAR(255) DEFAULT '' AFTER `ordem`,
ADD COLUMN `meta_descricao` VARCHAR(255) DEFAULT '' AFTER `meta_titulo`;

-- meta artigos
ALTER TABLE `artigos`
ADD COLUMN `meta_titulo` VARCHAR(255) DEFAULT '' AFTER `ordem`,
ADD COLUMN `meta_descricao` VARCHAR(255) DEFAULT '' AFTER `meta_titulo`;

-- meta empresas
ALTER TABLE `empresas`
ADD COLUMN `meta_titulo` VARCHAR(255) DEFAULT '' AFTER `cor_primaria`,
ADD COLUMN `meta_descricao` VARCHAR(255) DEFAULT '' AFTER `meta_titulo`;