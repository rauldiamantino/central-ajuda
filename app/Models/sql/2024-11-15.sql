-- adiciona coluna cor_primaria
ALTER TABLE `empresas` ADD COLUMN `cor_primaria` tinyint DEFAULT 1 AFTER `favicon`;

-- Índices FULLTEXT para busca completa
ALTER TABLE categorias ADD FULLTEXT(nome, descricao);
ALTER TABLE artigos ADD FULLTEXT(titulo);
ALTER TABLE conteudos ADD FULLTEXT(titulo);
ALTER TABLE conteudos ADD FULLTEXT(conteudo);