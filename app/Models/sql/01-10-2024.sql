CREATE TABLE `assinaturas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `empresa_id` INT NOT NULL,
  `valor` DECIMAL(10, 2) NOT NULL,
  `periodicidade` ENUM('mensal', 'anual') NOT NULL,
  `status` ENUM('ativa', 'cancelada') NOT NULL,
  `criado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresa_assinatura` (`empresa_id`, `id`),
  FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
);

CREATE TABLE `pagamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `assinatura_id` INT NOT NULL,
  `empresa_id` INT NOT NULL,
  `valor` DECIMAL(10, 2) NOT NULL,
  `data_pagamento` TIMESTAMP NULL,
  `status` ENUM('pago', 'pendente', 'atrasado') NOT NULL,
  `descricao` VARCHAR(255) NULL,
  `criado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_empresa_pagamento` (`empresa_id`, `id`),
  FOREIGN KEY (`assinatura_id`) REFERENCES `assinaturas` (`id`),
  FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
);
