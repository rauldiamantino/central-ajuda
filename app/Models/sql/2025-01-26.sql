CREATE TABLE visualizacoes (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `artigo_id` INT NOT NULL,
  `sessao_id` VARCHAR(255) NULL,
  `empresa_id` int NOT NULL,
  `criado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE INDEX `unico_artigo_sessao_empresa` (artigo_id, sessao_id, empresa_id)
);