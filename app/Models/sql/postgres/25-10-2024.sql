-- Criação do banco de dados no PostgreSQL
CREATE DATABASE "central_ajuda"
WITH ENCODING 'UTF8'
LC_COLLATE='en_US.UTF-8'
LC_CTYPE='en_US.UTF-8'
TEMPLATE template0;

-- Conecte-se ao banco de dados criado
-- \c central_ajuda

-- Tabela empresas
CREATE TABLE "central-ajuda".empresas (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  ativo INT DEFAULT 0,
  nome VARCHAR(255) DEFAULT NULL,
  subdominio VARCHAR(255) UNIQUE,
  cnpj VARCHAR(14) UNIQUE,
  telefone VARCHAR(11) DEFAULT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  sessao_stripe_id VARCHAR(255) DEFAULT '',
  assinatura_id VARCHAR(255) DEFAULT '',
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela usuarios
CREATE TABLE "central-ajuda".usuarios (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  ativo INT DEFAULT 0,
  nivel INT NOT NULL DEFAULT 2,
  empresa_id INT NOT NULL,
  padrao INT DEFAULT 0,
  nome VARCHAR(100) DEFAULT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  tentativas_login SMALLINT DEFAULT 0,
  ultimo_acesso JSON DEFAULT NULL,
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_empresa FOREIGN KEY (empresa_id) REFERENCES empresas (id) ON DELETE CASCADE
);

-- Tabela categorias
CREATE TABLE "central-ajuda".categorias (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  ativo INT DEFAULT 0,
  nome VARCHAR(255) NOT NULL,
  descricao TEXT,
  empresa_id INT NOT NULL,
  ordem INT NOT NULL,
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela artigos
CREATE TABLE "central-ajuda".artigos (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  ativo INT DEFAULT 0,
  titulo VARCHAR(255) NOT NULL,
  usuario_id INT DEFAULT NULL,
  empresa_id INT NOT NULL,
  categoria_id INT DEFAULT NULL,
  visualizacoes INT DEFAULT 0,
  ordem INT NOT NULL,
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT artigos_ibfk_1 FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
  CONSTRAINT artigos_ibfk_2 FOREIGN KEY (categoria_id) REFERENCES categorias (id)
);

-- Tabela conteudos
CREATE TABLE "central-ajuda".conteudos (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  ativo INT DEFAULT 0,
  artigo_id INT NOT NULL,
  empresa_id INT NOT NULL,
  tipo INT NOT NULL,
  titulo VARCHAR(255) DEFAULT NULL,
  titulo_ocultar INT DEFAULT 0,
  conteudo TEXT,
  url VARCHAR(255) DEFAULT NULL,
  ordem INT NOT NULL,
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT conteudos_ibfk_1 FOREIGN KEY (artigo_id) REFERENCES artigos (id) ON DELETE CASCADE
);

-- Tabela ajustes
CREATE TABLE "central-ajuda".ajustes (
  id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nome VARCHAR(255) DEFAULT NULL,
  ativo INT DEFAULT 0,
  empresa_id INT NOT NULL,
  criado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modificado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT unique_nome_empresa UNIQUE (nome, empresa_id)
);

-- Inserção da empresa padrão
INSERT INTO empresas (ativo, nome, subdominio)
VALUES (1, '360Help', 'padrao');

-- Inserção do usuário padrão
INSERT INTO usuarios (ativo, nivel, empresa_id, padrao, nome, email, senha)
VALUES (1, 1, 1, 99, 'Suporte - 360Help', 'suporte@360help.com.br', '$2y$10$QBvUmfoElypn.U/nUWxDw.fP4P3yKfQSYYol5azVg1cEQZPk4Zm.O');

-- Inserindo categorias
INSERT INTO categorias (ativo, nome, descricao, empresa_id, ordem, criado, modificado)
VALUES
    (1, 'Configuração da Conta', 'Artigos que orientam sobre como configurar e personalizar sua conta de forma eficaz.', 1, 1, NOW(), NOW()),
    (1, 'Segurança e Privacidade', 'Dicas e melhores práticas para garantir a segurança e privacidade dos usuários.', 1, 2, NOW(), NOW()),
    (1, 'Gerenciamento de Usuários', 'Guias sobre como criar e gerenciar usuários dentro da plataforma.', 1, 3, NOW(), NOW()),
    (1, 'Faturas e Cobranças', 'Informações detalhadas sobre faturas, cobranças e gerenciamento de pagamentos.', 1, 4, NOW(), NOW()),
    (1, 'Suporte ao Cliente', 'Orientações sobre como acessar e utilizar os recursos de suporte ao cliente.', 1, 5, NOW(), NOW()),
    (1, 'Relatórios e Análises', 'Tutoriais sobre como gerar e interpretar relatórios da sua conta.', 1, 6, NOW(), NOW()),
    (1, 'Integrações e Conexões', 'Informações sobre como integrar a plataforma com outros serviços e sistemas.', 1, 7, NOW(), NOW()),
    (1, 'Uso do Painel de Controle', 'Dicas e truques para navegar e utilizar o painel de controle de maneira eficaz.', 1, 8, NOW(), NOW()),
    (1, 'Dicas Úteis', 'Sugestões práticas para otimizar seu uso da plataforma e aumentar a produtividade.', 1, 9, NOW(), NOW()),
    (1, 'Atualizações e Novidades', 'Notícias sobre atualizações e novos recursos da plataforma.', 1, 10, NOW(), NOW()),
    ('1', 'Faturamento e Cobrança', 'Dicas e informações sobre como gerenciar faturas, realizar pagamentos e entender as cobranças.', '1', '11', NOW(), NOW()),
    ('1', 'Integrações e APIs', 'Orientações sobre como integrar o sistema com outras ferramentas e usar as APIs disponíveis.', '1', '12', NOW(), NOW()),
    ('1', 'Treinamento e Webinars', 'Recursos e agendamentos para treinamentos e webinars sobre o uso do sistema.', '1', '13', NOW(), NOW()),
    ('1', 'Feedback e Suporte', 'Como enviar feedback, entrar em contato com o suporte e sugestões de melhorias para o sistema.', '1', '14', NOW(), NOW()),
    ('1', 'Tecnologia', 'Artigos relacionados a área de tecnologia', '1', '15', NOW(), NOW());


-- Inserindo artigos
INSERT INTO artigos (ativo, titulo, usuario_id, empresa_id, categoria_id, visualizacoes, ordem, criado, modificado)
VALUES
    (1, 'Como Configurar Sua Conta Passo a Passo', 1, 1, 1, 0, 1, NOW(), NOW()),
    (1, 'Soluções Comuns para Problemas de Login', 1, 1, 1, 0, 2, NOW(), NOW()),
    (1, 'Atualizando Suas Informações Pessoais', 1, 1, 2, 0, 3, NOW(), NOW()),
    (1, 'Melhores Práticas de Segurança', 1, 1, 2, 0, 4, NOW(), NOW()),
    (1, 'Criando e Gerenciando Usuários', 1, 1, 3, 0, 5, NOW(), NOW()),
    (1, 'Explorando o Painel de Controle', 1, 1, 3, 0, 6, NOW(), NOW()),
    (1, 'Entendendo Suas Faturas', 1, 1, 4, 0, 7, NOW(), NOW()),
    (1, 'Acessando o Suporte ao Cliente', 1, 1, 5, 0, 8, NOW(), NOW()),
    (1, 'Criando Relatórios Personalizados', 1, 1, 6, 0, 9, NOW(), NOW()),
    (1, 'Integrando com Outros Sistemas', 1, 1, 7, 0, 10, NOW(), NOW()),
    (1, 'Como Utilizar a Central de Ajuda', 1, 1, 9, 0, 11, NOW(), NOW()),
    (1, 'Resolvendo Problemas Comuns', 1, 1, 10, 0, 11, NOW(), NOW()),
    (1, 'FAQ - Perguntas Frequentes', 1, 1, 11, 0, 12, NOW(), NOW()),
    (1, 'Dicas para Uso Eficiente', 1, 1, 12, 0, 13, NOW(), NOW()),
    (1, 'Maximize seu Aprendizado', 1, 1, 13, 0, 14, NOW(), NOW()),
    (1, 'Novas Funcionalidades', 1, 1, 14, 0, 15, NOW(), NOW()),
    (1, 'O Impacto da Tecnologia na Educação', 1, 1, 15, 0, 16, NOW(), NOW());

-- Inserindo conteúdos para cada artigo
INSERT INTO conteudos (ativo, artigo_id, empresa_id, tipo, titulo, titulo_ocultar, conteudo, url, ordem, criado, modificado)
VALUES
    -- Artigo 1
    (1, 1, 1, 1, 'Como Configurar Sua Conta', 0, '<p>Para configurar sua conta, siga este guia detalhado que aborda desde a criação até a personalização das suas preferências.</p>', NULL, 1, NOW(), NOW()),
    (1, 1, 1, 3, 'Vídeo Tutorial de Configuração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 2
    (1, 2, 1, 1, 'Resolvendo Problemas de Login', 0, '<p>Se você está tendo dificuldades para acessar sua conta, este artigo apresenta as soluções mais eficazes para os problemas mais comuns.</p>', NULL, 1, NOW(), NOW()),
    (1, 2, 1, 3, 'Vídeo Soluções de Login', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 3
    (1, 3, 1, 1, 'Mantendo Suas Informações Atualizadas', 0, '<p>Aprenda a manter suas informações sempre atualizadas, garantindo que sua conta esteja sempre precisa.</p>', NULL, 1, NOW(), NOW()),
    (1, 3, 1, 3, 'Vídeo de Atualização', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 4
    (1, 4, 1, 1, 'Dicas para Aumentar a Segurança', 0, '<p>Descubra as melhores práticas para manter sua conta segura, incluindo dicas sobre senhas e autenticação de dois fatores.</p>', NULL, 1, NOW(), NOW()),
    (1, 4, 1, 3, 'Vídeo de Segurança', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 5
    (1, 5, 1, 1, 'Como Criar e Gerenciar Usuários', 0, '<p>Um guia completo sobre como adicionar, editar e remover usuários no seu sistema.</p>', NULL, 1, NOW(), NOW()),
    (1, 5, 1, 3, 'Vídeo de Gerenciamento de Usuários', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 6
    (1, 6, 1, 1, 'Navegando pelo Painel de Controle', 0, '<p>Este artigo oferece uma visão geral do painel de controle e como usá-lo de forma eficiente.</p>', NULL, 1, NOW(), NOW()),
    (1, 6, 1, 3, 'Vídeo de Navegação', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 7
    (1, 7, 1, 1, 'Entendendo Faturas e Pagamentos', 0, '<p>Informações sobre suas faturas, como interpretá-las e realizar pagamentos.</p>', NULL, 1, NOW(), NOW()),
    (1, 7, 1, 3, 'Vídeo de Faturas', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 8
    (1, 8, 1, 1, 'Acessando Suporte ao Cliente', 0, '<p>Orientações sobre como entrar em contato com o suporte e resolver problemas rapidamente.</p>', NULL, 1, NOW(), NOW()),
    (1, 8, 1, 3, 'Vídeo de Suporte', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 9
    (1, 9, 1, 1, 'Gerando Relatórios Personalizados', 0, '<p>Aprenda a gerar relatórios personalizados para análise de dados.</p>', NULL, 1, NOW(), NOW()),
    (1, 9, 1, 3, 'Vídeo de Relatório', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 10
    (1, 10, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 10, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 11
    (1, 11, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 11, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 12
    (1, 12, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 12, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 13
    (1, 13, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 13, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 14
    (1, 14, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 14, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 15
    (1, 15, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 15, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 16
    (1, 16, 1, 1, 'Integrações com Outros Sistemas', 0, '<p>Informações sobre como integrar a plataforma com outros serviços.</p>', NULL, 1, NOW(), NOW()),
    (1, 16, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW()),

    -- Artigo 17
    (1, 17, 1, 1, 'O Impacto da Tecnologia na Educação', 0, '<h2>Introdução</h2><p>Nos últimos anos, a tecnologia tem desempenhado um papel crucial na transformação da educação. Com o advento de ferramentas digitais e acesso à internet, a forma como os alunos aprendem e os professores ensinam mudou significativamente. Este artigo explora as vantagens, desvantagens e as futuras tendências da tecnologia na educação.</p><h2>Vantagens da Tecnologia na Educação</h2><h3>1. Acesso à Informação</h3><p>A tecnologia permite que alunos tenham acesso a uma vasta gama de informações de forma rápida e fácil. Plataformas como Google Scholar, Coursera e Khan Academy oferecem recursos valiosos para o aprendizado.</p><ul><li><strong>Exemplo de recursos:</strong></li><ul><li><strong>Khan Academy:</strong> Cursos de matemática, ciências e muito mais.</li><li><strong>Coursera:</strong> Cursos online de universidades renomadas.</li></ul></ul><h3>2. Aprendizado Personalizado</h3><p>As ferramentas tecnológicas possibilitam um aprendizado adaptativo, onde o conteúdo pode ser ajustado conforme o nível de habilidade do aluno. Isso ajuda a atender às necessidades individuais de cada estudante.</p><h3>3. Colaboração e Comunicação</h3><p>Com a tecnologia, a colaboração entre alunos e professores se torna mais fácil e eficiente. Ferramentas como Google Classroom e Microsoft Teams facilitam a comunicação e o compartilhamento de recursos.</p><h4>Exemplo de Ferramentas:</h4><ul><li><strong>Google Classroom:</strong> Plataforma de gerenciamento de aprendizagem que permite a interação entre professores e alunos.</li><li><strong>Microsoft Teams:</strong> Ferramenta de colaboração que integra chat, videoconferências e compartilhamento de arquivos.</li></ul><h2>Desvantagens da Tecnologia na Educação</h2><h3>1. Distrações</h3><p>Embora a tecnologia ofereça muitos benefícios, ela também pode ser uma fonte de distração. A presença de redes sociais e jogos pode desviar a atenção dos alunos durante as aulas.</p><h3>2. Desigualdade no Acesso</h3><p>Nem todos os alunos têm o mesmo acesso à tecnologia. Isso pode criar uma lacuna no aprendizado, onde alguns alunos se beneficiam mais do que outros.</p><h3>3. Dependência da Tecnologia</h3><p>A dependência excessiva da tecnologia pode prejudicar o desenvolvimento de habilidades críticas, como pensamento crítico e resolução de problemas.</p><h2>Futuras Tendências</h2><h3>1. Aprendizado Híbrido</h3><p>O modelo de aprendizado híbrido, que combina ensino presencial e online, deve se tornar mais comum. Essa abordagem oferece flexibilidade e permite que os alunos aprendam em seu próprio ritmo.</p><h3>2. Inteligência Artificial</h3><p>A inteligência artificial (IA) está começando a ser incorporada em ferramentas educacionais, permitindo personalização em larga escala. Sistemas baseados em IA podem adaptar o conteúdo conforme o desempenho do aluno.</p><h3>3. Realidade Aumentada e Virtual</h3><p>A realidade aumentada (RA) e a realidade virtual (RV) têm o potencial de transformar a educação, oferecendo experiências imersivas que podem enriquecer o aprendizado.</p><h2>Conclusão</h2><p>A tecnologia, quando utilizada de forma eficaz, pode revolucionar a educação. No entanto, é crucial abordar as desvantagens e garantir que todos os alunos tenham acesso equitativo às ferramentas necessárias. O futuro da educação dependerá da nossa capacidade de integrar a tecnologia de maneira responsável e inovadora.</p>', NULL, 1, NOW(), NOW()),
    (1, 17, 1, 3, 'Vídeo de Integração', 0, NULL, 'https://www.youtube.com/watch?v=KBuAh0JRcRI', 3, NOW(), NOW());