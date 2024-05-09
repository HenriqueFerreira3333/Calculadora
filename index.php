 <?php
session_start();

if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['M'])) {
        if (isset($_SESSION['num1'], $_SESSION['num2'], $_SESSION['operacao'])) {
            $_POST['num1'] = $_SESSION['num1'];
            $_POST['num2'] = $_SESSION['num2'];
            $_POST['operacao'] = $_SESSION['operacao'];
        } else {
            $_SESSION['mensagem'] = "<p>Nenhum valor salvo para recuperar!</p>";
        }
    } elseif (isset($_POST['salvar'])) {
        if (isset($_POST['num1'], $_POST['num2'], $_POST['operacao'])) {
            $_SESSION['num1'] = $_POST['num1'];
            $_SESSION['num2'] = $_POST['num2'];
            $_SESSION['operacao'] = $_POST['operacao'];
            $_SESSION['mensagem'] = "<p>Valores salvos!</p>";
        } else {
            $_SESSION['mensagem'] = "<p>Nenhum valor para salvar!</p>";
        }
    } elseif (isset($_POST['recuperar'])) {
        $_POST['num1'] = $_SESSION['num1'] ?? '';
        $_POST['num2'] = $_SESSION['num2'] ?? '';
        $_POST['operacao'] = $_SESSION['operacao'] ?? '';
    } elseif (isset($_POST['limpar_historico'])) {
        $_SESSION['historico'] = array();
        $_SESSION['mensagem'] = "<p>Histórico apagado!</p>";
    } else {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operacao = $_POST['operacao'];

        $resultado = calcular($num1, $num2, $operacao);

        $historico_item = array(
            'num1' => $num1,
            'num2' => $num2,
            'operacao' => $operacao,
            'resultado' => $resultado
        );
        $_SESSION['historico'][] = $historico_item;

        $_SESSION['mensagem'] = "<p>O resultado é: $resultado</p>";
    }
}

function calcular($num1, $num2, $operacao)
{
    switch ($operacao) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Divisão por zero";
            }
        case '^':
            return pow($num1, $num2);
        case '!':
            if ($num1 < 0 || $num1 != intval($num1)) {
                return "Fatorial não definido.";
            }
            $resultado = 1;
            for ($i = 1; $i <= $num1; $i++) {
                $resultado *= $i;
            }
            return $resultado;
        default:
            return "Operação inválida";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .calculator {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .calculator h2 {
            text-align: center;
        }

        .calculator form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .calculator input[type="text"],
        .calculator select {
            width: 45%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .calculator select {
            width: auto;
        }

        .calculator button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .calculator button:hover {
            background-color: #0056b3;
        }

        .calculator ul {
            margin-top: 20px;
            padding: 0;
            list-style-type: none;
        }

        .calculator ul li {
            margin-bottom: 5px;
        }

        .calculator .result {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="calculator">
        <h2>Calculadora</h2>
        <form method="post">
            <input type="text" name="num1" placeholder="Primeiro número" value="<?php echo $_POST['num1'] ?? ''; ?>">
            <select name="operacao">
                <option value="+" <?php echo ($_POST['operacao'] ?? '') == '+' ? 'selected' : ''; ?>>+</option>
                <option value="-" <?php echo ($_POST['operacao'] ?? '') == '-' ? 'selected' : ''; ?>>-</option>
                <option value="" <?php echo ($_POST['operacao'] ?? '') == 'x' ? 'selected' : ''; ?>></option>
                <option value="/" <?php echo ($_POST['operacao'] ?? '') == '/' ? 'selected' : ''; ?>>/</option>
                <option value="^" <?php echo ($_POST['operacao'] ?? '') == '^' ? 'selected' : ''; ?>>^</option>
                <option value="!" <?php echo ($_POST['operacao'] ?? '') == '!' ? 'selected' : ''; ?>>!</option>
            </select>
            <input type="text" name="num2" placeholder="Segundo número" value="<?php echo $_POST['num2'] ?? ''; ?>">
            <button type="submit">Calcular</button>
            <button type="submit" name="salvar">Salvar</button>
            <button type="submit" name="recuperar">Recuperar</button>
            <button type="submit" name="M">M</button>
            <button type="submit" name="limpar_historico">Limpar Histórico</button>
        </form>
        <h3>Histórico</h3>
        <ul>
            <?php foreach ($_SESSION['historico'] as $item) : ?>
                <li><?php echo "{$item['num1']} {$item['operacao']} {$item['num2']} = {$item['resultado']}"; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php if(isset($_SESSION['mensagem'])): ?>
            <div class="result">
                <?php echo $_SESSION['mensagem']; ?>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
    </div>
</body>

</html>
