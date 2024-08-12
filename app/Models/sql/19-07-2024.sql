CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
)

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nivel` int NOT NULL DEFAULT '2',
  `empresa_id` int NOT NULL,
  `padrao` int DEFAULT '0',
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `empresa_padrao_unico` (`empresa_id`, `padrao`)
  KEY `fk_usuarios_empresa` (`empresa_id`),
  CONSTRAINT `fk_usuarios_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
)

CREATE TABLE `artigos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `titulo` varchar(255) NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `empresa_id` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `visualizacoes` int DEFAULT '0',
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `artigos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `artigos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
)

CREATE TABLE `conteudos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `artigo_id` int NOT NULL,
  `empresa_id` int NOT NULL,
  `tipo` int NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `titulo_ocultar` int DEFAULT '0',
  `conteudo` text,
  `url` varchar(255) DEFAULT NULL,
  `ordem` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `artigo_id` (`artigo_id`),
  CONSTRAINT `conteudos_ibfk_1` FOREIGN KEY (`artigo_id`) REFERENCES `artigos` (`id`) ON DELETE CASCADE
)


CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ativo` int DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `empresa_id` int NOT NULL,
  `criado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)