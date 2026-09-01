<?php

// =========================== 1) SESSÃO ======================================

session_start();

// ======================= 2) CONFIGURAÇÃO BÁSICA =============================

$arquivo_log = __DIR__ . '\log.txt';
$feedback = '';
$feedback_classe = '';

// =============== 3) PROCESSAMENTO DO FORMULÁRIO (MÉTODO POST) ===============

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $_POST['mensagem'] ?? '';

    $mensagem = trim($mensagem);
    $mensagem = str_replace(["\r\n", "\r", "\n"], ' ', $mensagem);
    $mensagem = trim($mensagem);

    // ------------------------- VALIDAÇÃO ----------------------------------
    if ($mensagem === '') {

        $feedback = 'Po, escreve alguma coisa aí! Vazio não dá!';
        $feedback_classe = 'erro';
    } else {

        // ----------------------- MONTA A LINHA ----------------------------

        $linha = '[' . date('d/m/Y H:i:s') . '] ' . $mensagem . PHP_EOL;

        // ----------------------- ESCREVE NO ARQUIVO ------------------------

        $resultado = file_put_contents($arquivo_log, $linha, FILE_APPEND | LOCK_EX);

        if ($resultado === false) {

            $feedback = 'ERRO: não cnsegui escrever no arquivo, vira-se aí!';
            $feedback_classe = 'erro';
        } else {

            // ------------------- PADRÃO PRG (IMPORTANTE!) -----------------

            $_SESSION['feedback'] = [
                'texto' => 'log salvo com sucesso! 👌',
                'tipo' => 'sucesso',
            ];
            header('Location: index.php');
            exit;
        }
    }
}

// ------------------- CONSUME A MENSAGEM DA SESSÃO ---------------------------

if (isset($_SESSION['feedback'])) {
    if (is_array($_SESSION['feedback'])) {

        $feedback        = $_SESSION['feedback']['texto'];
        $feedback_classe = $_SESSION['feedback']['tipo'];
    } else {

        $feedback = (string) $_SESSION['feedback'];
        $feedback_classe = 'sucesso';
    }

    unset($_SESSION['feedback']);
}

// ======================= 4) LEITURA DOS LOGS SALVOS =========================


$conteudo = file_get_contents($arquivo_log);

if ($conteudo === false) {
    $conteudo = '';
}

$linhas = explode(PHP_EOL, trim($conteudo));

$linhas = array_filter($linhas, function ($linha) {
    return $linha !== '';
});

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Log — PHP + TXT</title>
    <style>
        /* ---------- CSS simples para deixar o sistema apresentável ---------- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            color: #1f2937;
            min-height: 100vh;
            padding: 2.5rem 1rem;
        }

        main {
            max-width: 720px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: .25rem;
        }

        .subtitulo {
            color: #6b7280;
            margin-bottom: 1.25rem;
        }

        .subtitulo code {
            background: #e5e7eb;
            padding: .1rem .35rem;
            border-radius: 4px;
            font-size: .9em;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .08);
            margin-bottom: 2rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: .5rem;
        }

        textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: .75rem;
            font: inherit;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .18);
        }

        button {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: .7rem 1.5rem;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: .75rem;
        }

        button:hover {
            background: #1d4ed8;
        }

        .feedback {
            padding: .8rem 1.1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-weight: 500;
        }

        .feedback.sucesso {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .feedback.erro {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .log {
            background: #ffffff;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
            padding: .9rem 1.1rem;
            margin-bottom: .75rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .06);
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.5;
        }

        .vazio {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem 1rem;
            text-align: center;
            color: #6b7280;
            font-style: italic;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .06);
        }
    </style>
</head>

<body>
    <main>
        <h1>📝 Meu Sistema de Log</h1>
        <p class="subtitulo">Cada salvamento acrescenta uma linha ao arquivo <code>log.txt</code>.</p>

        <?php

        if ($feedback !== '') {

            echo '<div class="feedback ' . $feedback_classe . '">'
                . htmlspecialchars($feedback)
                . '</div>';
        }

        ?>

        <!-- O FORMULÁRIO: method="POST" envia os dados para a própria página -->
        <div class="card">
            <form method="POST" action="index.php">
                <label for="mensagem">O que você quer registrar hoje?</label>
                <textarea name="mensagem" id="mensagem" rows="4"
                    placeholder="Ex.: Hoje entendi como o file_put_contents funciona! 😄"
                    required></textarea>
                <button type="submit">💾 Salvar log</button>
            </form>
        </div>

        <!-- A LISTA DE LOGS SALVOS -->
        <h2>📚 Logs anteriores</h2>

        <section class="logs">
            <?php

            if (count($linhas) === 0) {

                echo '<p class="vazio">Nenhum log por aqui ainda... Vai ser o primeiro! 😉</p>';
            } else {

                foreach ($linhas as $linha) {

                    echo '<div class="log">' . htmlspecialchars($linha) . '</div>';
                }
            }

            ?>
        </section>
    </main>
</body>

</html>