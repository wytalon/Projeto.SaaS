<?php
// Desativa a exibição de erros para o usuário por motivos de segurança em ambiente de produção
ini_set('display_errors', 0);
error_reporting(0);

// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Configurações do Banco de Dados ---
    // Você DEVE substituir estas variáveis com as suas próprias credenciais.
    $host: = "db.nbzmzcmgpcafjebtdgja.supabase.co";
    $port: = "5432";
    $database: = "postgres";
    $user: = "postgres";

    // Conecta-se ao banco de dados
    $conn = new mysqli($host, $port, $database, $user);

    // Verifica a conexão
    if ($conn->connect_error) {
        // Loga o erro em vez de exibi-lo
        error_log("Erro de Conexão com o Banco de Dados: " . $conn->connect_error);
        die("<div class='text-center p-8 bg-red-100 text-red-700 rounded-lg shadow-lg'>Erro: Não foi possível conectar ao banco de dados.</div>");
    }

    // Coleta e sanitiza os dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $produto = $conn->real_escape_string($_POST['produto']);
    $valor = floatval($_POST['valor']);

    // Prepara a query SQL com uma instrução preparada para prevenir injeção de SQL
    $sql = "INSERT INTO compras (nome_cliente, email_cliente, telefone_cliente, produto_comprado, valor_total, data_compra) VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    
    // Se a preparação da query falhar
    if ($stmt === false) {
        error_log("Erro na Preparação da Query: " . $conn->error);
        die("<div class='text-center p-8 bg-red-100 text-red-700 rounded-lg shadow-lg'>Erro: Ocorreu um problema ao processar sua solicitação.</div>");
    }

    // Associa as variáveis aos parâmetros da query
    $stmt->bind_param("ssssd", $nome, $email, $telefone, $produto, $valor);

    // Executa a query
    if ($stmt->execute()) {
        echo "<div class='text-center p-8 bg-green-100 text-green-700 rounded-lg shadow-lg'>Dados registrados com sucesso!</div>";
    } else {
        error_log("Erro na Execução da Query: " . $stmt->error);
        echo "<div class='text-center p-8 bg-red-100 text-red-700 rounded-lg shadow-lg'>Erro: Não foi possível registrar os dados.</div>";
    }

    // Fecha a instrução e a conexão
    $stmt->close();
    $conn->close();

} else {
    // Redireciona o usuário de volta ao formulário se a requisição não for POST
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- O resultado do PHP será exibido aqui -->
</body>
</html>
