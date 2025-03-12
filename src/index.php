<?php

// Defina o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Carregue as variáveis de ambiente
$nominatimApiUrl = getenv('NOMINATIM_API_URL');

// Função para registrar logs
function registrarLog($mensagem) {
    $logFile = __DIR__ . '/logs/requests.log'; // Caminho do arquivo de log
    $dataHora = date('Y-m-d H:i:s');
    $mensagemFormatada = "[$dataHora] $mensagem\n";
    
    file_put_contents($logFile, $mensagemFormatada, FILE_APPEND);
}

// Função para realizar a requisição cURL
function fazerRequisicaoCurl($url) {
    $ch = curl_init();  // Inicializa a sessão cURL
    curl_setopt($ch, CURLOPT_URL, $url);  // Define a URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Retorna o resultado como string
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: MeuApp/1.0'  // Cabeçalho User-Agent
    ));
    $response = curl_exec($ch);  // Executa a requisição
    curl_close($ch);  // Fecha a sessão cURL
    return $response;
}

// Verifique se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados JSON enviados na requisição
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Log da requisição recebida
    registrarLog("Requisição recebida: " . json_encode($inputData));

    // Verifique se ao menos um dos parâmetros foi enviado (endereço ou lat/lon)
    if ((isset($inputData['endereco']) && !empty($inputData['endereco'])) || 
        (isset($inputData['latitude']) && isset($inputData['longitude']))) {
        
        // Se for pesquisa por endereço
        if (isset($inputData['endereco']) && !empty($inputData['endereco'])) {
            $endereco = $inputData['endereco'];
            $url = $nominatimApiUrl . "/search?q=" . urlencode($endereco) . "&format=json&limit=1";
        }
        
        // Se for pesquisa por latitude/longitude
        elseif (isset($inputData['latitude']) && isset($inputData['longitude'])) {
            $lat = $inputData['latitude'];
            $lon = $inputData['longitude'];
            $url = $nominatimApiUrl . "/reverse?lat=" . urlencode($lat) . "&lon=" . urlencode($lon) . "&format=json";
        }

        // Realize a requisição GET ao Nominatim usando cURL
        $response = fazerRequisicaoCurl($url);
        $data = json_decode($response, true);

        // Verifique se o Nominatim retornou dados
        if (!empty($data)) {
            // URL do Google Maps
            $googleMapsUrl = "https://www.google.com/maps?q=" . urlencode($data[0]['lat']) . "," . urlencode($data[0]['lon']);

            // Log de sucesso
            registrarLog("Resposta de sucesso: " . json_encode($data));

            // Envie os dados encontrados de volta, incluindo a URL do Google Maps
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            // Log de erro
            registrarLog("Erro: Endereço ou coordenadas não encontrados.");

            // Caso não encontre nenhum endereço
            echo json_encode([
                'status' => 'error',
                'message' => 'Endereço ou coordenadas não encontrados.'
            ]);
        }
    } else {
        // Log de erro
        registrarLog("Erro: Nenhum parâmetro de endereço ou coordenadas fornecido.");

        // Caso nenhum parâmetro seja enviado
        echo json_encode([
            'status' => 'error',
            'message' => 'É necessário fornecer um endereço ou coordenadas (latitude/longitude).'
        ]);
    }
} else {
    // Log de erro
    registrarLog("Erro: Método não permitido. Apenas POST é aceito.");

    // Caso não seja uma requisição POST
    echo json_encode([
        'status' => 'error',
        'message' => 'Método não permitido. Apenas POST é aceito.'
    ]);
}
?>
