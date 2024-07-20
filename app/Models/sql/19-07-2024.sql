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
  KEY `fk_usuarios_empresa` (`empresa_id`),
  CONSTRAINT `fk_usuarios_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
)