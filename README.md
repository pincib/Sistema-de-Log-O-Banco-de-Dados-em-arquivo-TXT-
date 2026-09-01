# 📝 PHP Log System — Database Simulado em TXT

Este projeto foi desenvolvido durante o meu 5º dia de imersão em PHP. O objetivo principal foi criar um sistema de persistência de dados funcional sem a utilização de bancos de dados SQL ou Programação Orientada a Objetos (POO), focando puramente em **PHP Procedural** e **Lógica de Programação**.

## 🚀 Sobre o Projeto

O sistema permite que o usuário registre mensagens (logs) que são armazenadas permanentemente em um arquivo de texto (`log.txt`). Ao contrário de variáveis comuns que "morrem" ao recarregar a página, este projeto utiliza o sistema de arquivos do servidor para simular o comportamento de uma tabela de banco de dados.

### 🧠 Conceitos Aplicados

Durante o desenvolvimento, implementei conceitos fundamentais de desenvolvimento web backend:

*   **Persistência em Arquivos:** Uso de `file_put_contents` com a flag `FILE_APPEND` para salvar novos dados sem apagar os antigos e `file_get_contents` para leitura.
*   **Padrão PRG (Post-Redirect-Get):** Implementação de redirecionamento após o envio do formulário para evitar a duplicação de dados ao atualizar a página (F5).
*   **Gestão de Sessões (Flash Messages):** Uso de `$_SESSION` para exibir mensagens de feedback (sucesso/erro) que desaparecem após serem visualizadas.
*   **Sanitização e Segurança:** 
    *   Uso de `trim()` e `str_replace()` para limpar entradas do usuário.
    *   Proteção contra ataques **XSS** utilizando `htmlspecialchars()` na exibição dos dados.
*   **Manipulação de Arrays:** Uso de `explode()` para converter o arquivo de texto em um array manipulável e `array_filter()` para limpeza de dados.

## 🛠️ Tecnologias e Funções Utilizadas

*   **Linguagem:** PHP 8+
*   **Frontend:** HTML5 e CSS3 (layout responsivo e moderno).
*   **Funções Chave:**
    *   `session_start()`, `isset()`, `unset()`
    *   `file_put_contents()`, `file_get_contents()`
    *   `explode()`, `trim()`, `str_replace()`
    *   `date()`, `header()`, `exit()`

## 📂 Como funciona a "Tabela TXT"

O sistema segue a lógica de que **1 linha no arquivo = 1 registro no banco**.
O formato de armazenamento definido foi:
`[DIA/MÊS/ANO HORA:MINUTO:SEGUNDO] Mensagem do Usuário`

---
> **Nota de estudo:** Este projeto faz parte da minha trilha de aprendizado de PHP. Após dominar a manipulação de arquivos e sessões, o próximo passo será a migração desta lógica para bancos de dados relacionais (MySQL) e Programação Orientada a Objetos (POO).
