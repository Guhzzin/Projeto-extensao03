CREATE TABLE usuarios (
    xp INT DEFAULT 0,
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    status ENUM('pendente', 'andamento', 'concluida') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela para guardar o estoque de perguntas
CREATE TABLE banco_questoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(50) NOT NULL, -- ex: 'soma', 'subtracao', 'multiplicacao', 'divisao'
    dificuldade ENUM('iniciante', 'intermediario', 'veterano') NOT NULL DEFAULT 'iniciante',
    enunciado TEXT NOT NULL,
    -- Opções (Deixei até 4 opções, se a questão for de verdadeiro/falso, você só preenche A e B)
    opcao_a VARCHAR(255) NOT NULL,
    opcao_b VARCHAR(255) NOT NULL,
    opcao_c VARCHAR(255),
    opcao_d VARCHAR(255),
    resposta_correta VARCHAR(5) NOT NULL, -- Vai guardar 'A', 'B', 'C' ou 'D'
    xp_recompensa INT DEFAULT 10,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Inserindo questões de SOMA
INSERT INTO banco_questoes (categoria, dificuldade, enunciado, opcao_a, opcao_b, opcao_c, opcao_d, resposta_correta, xp_recompensa) VALUES
('soma', 'iniciante', 'Quanto é 5 + 7?', '10', '11', '12', '13', 'C', 10),
('soma', 'iniciante', 'Maria tem 3 maçãs e ganhou mais 4. Quantas maçãs Maria tem agora?', '6', '7', '8', '9', 'B', 10),
('soma', 'intermediario', 'Se eu somar 45 com 28, qual o resultado?', '63', '73', '83', '93', 'B', 20),
('soma', 'veterano', 'Qual é o resultado da soma de 1.450 + 2.675?', '4.025', '4.125', '4.225', '4.325', 'B', 50),
('soma', 'iniciante', 'Quanto é 8 + 6?', '12', '13', '14', '15', 'C', 10),
('soma', 'iniciante', 'Se você tem 15 figurinhas e compra mais 10, com quantas você fica?', '25', '30', '20', '35', 'A', 10),
('soma', 'iniciante', 'Qual é o resultado de 22 + 7?', '27', '28', '29', '30', 'C', 10),
('soma', 'iniciante', 'Na fruteira há 12 bananas e 15 maçãs. Quantas frutas há no total?', '25', '26', '27', '28', 'C', 10),
('soma', 'iniciante', 'Quanto é 50 + 50?', '90', '100', '110', '120', 'B', 10),
('soma', 'iniciante', 'Resolva: 9 + 9 = ?', '18', '19', '20', '21', 'A', 10),
('soma', 'iniciante', 'Lucas tinha R$ 20 e ganhou R$ 15 de sua avó. Quanto ele tem agora?', 'R$ 30', 'R$ 35', 'R$ 40', 'R$ 45', 'B', 10),
('soma', 'iniciante', 'Qual o valor de 100 + 25?', '115', '120', '125', '130', 'C', 10),
('soma', 'iniciante', 'O resultado de 5 + 5 é igual a 10.', 'Verdadeiro', 'Falso', 'A', 10),
('soma', 'iniciante', 'Se eu somar 7 + 2, o resultado será 8.', 'Verdadeiro', 'Falso', 'B', 10),
('soma', 'iniciante', 'Somar zero (0) a qualquer número não altera o valor dele.', 'Verdadeiro', 'Falso', 'A', 10),
('soma', 'iniciante', 'A conta 10 + 10 tem como resultado o número 30.', 'Verdadeiro', 'Falso', 'B', 10),
('soma', 'intermediario', 'Qual é o resultado da soma de 156 + 284?', '430', '440', '450', '460', 'B', 20),
('soma', 'intermediario', 'Uma escola tem 345 alunos no período da manhã e 478 no período da tarde. Qual o total de alunos?', '813', '823', '833', '843', 'B', 20),
('soma', 'intermediario', 'Resolva: 589 + 134 = ?', '713', '723', '733', '743', 'B', 20),
('soma', 'intermediario', 'Carlos comprou um celular por R$ 845 e uma capinha por R$ 67. Quanto ele gastou ao todo?', 'R$ 902', 'R$ 912', 'R$ 922', 'R$ 932', 'B', 20),
('soma', 'intermediario', 'O dobro de 150 somado com 50 é igual a 350.', 'Verdadeiro', 'Falso', NULL, NULL, 'A', 20),
('soma', 'intermediario', 'Somando 425 e 375, obtemos um número maior que 850.', 'Verdadeiro', 'Falso', NULL, NULL, 'B', 20),
('soma', 'veterano', 'Calcule o valor exato de 4.567 + 3.892:', '8.359', '8.459', '8.559', '8.659', 'B', 50),
('soma', 'veterano', 'Uma empresa lucrou R$ 15.450 em janeiro, R$ 22.300 em fevereiro e R$ 18.900 em março. Qual foi o lucro total no trimestre?', 'R$ 55.650', 'R$ 56.650', 'R$ 57.650', 'R$ 58.650', 'B', 50),
('soma', 'veterano', 'Resolva a expressão com três parcelas: 12.458 + 9.874 + 356 = ?', '22.588', '22.688', '22.788', '22.888', 'B', 50),
('soma', 'veterano', 'Qual é a soma de 89.456 + 12.345?', '101.701', '101.801', '101.901', '102.801', 'B', 50),
('soma', 'veterano', 'A soma de 15.000 + 25.500 + 10.500 resulta em um valor estritamente maior que 50.000.', 'Verdadeiro', 'Falso', NULL, NULL, 'A', 50),
('soma', 'veterano', 'A soma de dois números ímpares gigantes, como 15.347 e 9.871, resultará sempre em outro número ímpar.', 'Verdadeiro', 'Falso', NULL, NULL, 'B', 50);

-- Inserindo uma de SUBTRAÇÃO (Apenas para exemplo de Verdadeiro ou Falso)
INSERT INTO banco_questoes (categoria, dificuldade, enunciado, opcao_a, opcao_b, resposta_correta, xp_recompensa) VALUES
('subtracao', 'iniciante', 'A subtração de 10 - 5 resulta em um número ímpar?', 'Verdadeiro', 'Falso', 'A', 10);

-- Tabela para registrar o histórico de acertos/erros de cada aluno
CREATE TABLE historico_respostas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    questao_id INT NOT NULL,
    acertou BOOLEAN NOT NULL, -- 1 se acertou, 0 se errou
    respondido_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (questao_id) REFERENCES banco_questoes(id)
);

