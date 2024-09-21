--- tabela usuarios
ALTER TABLE `usuarios` ADD COLUMN `tentativas_login` tinyint DEFAULT 0 AFTER `senha`, ADD COLUMN `ultimo_acesso` JSON AFTER `tentativas_login`;