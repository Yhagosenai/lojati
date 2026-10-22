<?php
require "config.php";
header("Content-Type: application/json; charset=UTF-8");

$rota = $_GET["rota"] ?? (
    $_SERVER["REQUEST_METHOD"] === "POST" ? "clientes/create" : "teste"
);

function teste() {
    echo json_encode(["mensagem" => "Back-end respondendo"]);
}
function createClientes($con) {
    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $endereco = $_POST["endereco"] ?? "";

    try {
        $stmt = $con->prepare(
            "INSERT INTO Clientes (nome, email, telefone, endereco)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $nome,
            $email,
            $telefone,
            $endereco
        ]);
        echo json_encode([
            "mensagem" => "Cliente cadastrado com sucesso!"
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "erro" => "Falha ao cadastrar cliente."
        ]);
    }
}
function readClientes($con) {
    $stmt = $con->query("SELECT * FROM Clientes");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && $rota === "clientes/create") {
    createClientes($con);
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $rota === "clientes") {
    readClientes($con);
} elseif ($rota === "teste") {
    teste();
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>