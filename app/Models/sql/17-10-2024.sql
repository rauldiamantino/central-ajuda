-- Database Local
-- CREATE DATABASE `central-ajuda-teste` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `central-ajuda-teste`;

-- Database Produção
CREATE DATABASE `central-ajuda` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `central-ajuda`;


CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `subdominio` varchar(255) DEFAULT NULL,
  `cnpj` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sessao_stripe_id` varchar(255) DEFAULT '',
  `assinatura_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`),
  UNIQUE KEY `subdominio` (`subdominio`)
);

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nivel` int NOT NULL DEFAULT '2',
  `empresa_id` int NOT NULL,
  `padrao` int DEFAULT '0',
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tentativas_login` tinyint DEFAULT '0',
  `ultimo_acesso` json DEFAULT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_usuarios_empresa` (`empresa_id`),
  CONSTRAINT `fk_usuarios_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
);

CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `empresa_id` int NOT NULL,
  `ordem` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `artigos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `titulo` varchar(255) NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `empresa_id` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `visualizacoes` int DEFAULT '0',
  `ordem` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `titulo` (`titulo`) USING BTREE,
  KEY `usuario_id` (`usuario_id`) USING BTREE,
  CONSTRAINT `artigos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `artigos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
);

CREATE TABLE `conteudos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `artigo_id` int NOT NULL,
  `empresa_id` int NOT NULL,
  `tipo` int NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `titulo_ocultar` int DEFAULT '0',
  `conteudo` text,
  `url` varchar(255) DEFAULT NULL,
  `ordem` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `artigo_id` (`artigo_id`),
  CONSTRAINT `conteudos_ibfk_1` FOREIGN KEY (`artigo_id`) REFERENCES `artigos` (`id`) ON DELETE CASCADE
);

CREATE TABLE `ajustes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ativo` int DEFAULT '0',
  `empresa_id` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nome_empresa` (`nome`,`empresa_id`)
);

-- Empresa Padrão
INSERT INTO
    `empresas` (ativo, subdominio)
VALUES
    ('1', 'padrao');

-- Usuário padrão
INSERT INTO
    `usuarios` (`ativo`,`nivel`,`empresa_id`,`padrao`,`email`,`senha`)
VALUES
    ('1','1','1','99','suporte@360help.com.br','$2y$10$QBvUmfoElypn.U/nUWxDw.fP4P3yKfQSYYol5azVg1cEQZPk4Zm.O');