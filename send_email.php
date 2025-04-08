<?php
// Inicia o output buffering apenas se necessário
if(isset($_POST['name'])) {
    ob_start();
}

// Carrega apenas as classes necessárias usando autoload
require __DIR__ . '/vendor/autoload.php';

// Define constantes para mensagens de erro
define('ERROR_MESSAGE', 'Erro ao enviar a mensagem.');
define('INVALID_REQUEST', 'Método de requisição inválido.');

// Verifica o método de requisição antes de processar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Carrega variáveis de ambiente apenas quando necessário
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad(); // Usa safeLoad para não falhar se o arquivo não existir
    
    // Filtra e sanitiza os dados do formulário de uma vez
    $formData = filter_input_array(INPUT_POST, [
        'name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'surname' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL,
        'about' => FILTER_SANITIZE_SPECIAL_CHARS,
        'message' => FILTER_SANITIZE_SPECIAL_CHARS
    ]);
    
    // Valida o e-mail
    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        die('E-mail inválido.');
    }
    
    // Envia o e-mail usando uma função separada para melhor organização
    sendEmail($formData);
} else {
    echo INVALID_REQUEST;
}

/**
 * Função para enviar e-mail com os dados do formulário
 * @param array $data Os dados do formulário
 * @return void
 */
function sendEmail($data) {
    try {
        // Importa as classes necessárias dentro da função para economizar memória
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuração do servidor SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];
        
        // Configuração do remetente e destinatário
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($_ENV['MAIL_TO']);
        $mail->addReplyTo($data['email'], $data['name'] . ' ' . $data['surname']);
        
        // Configuração da mensagem
        $mail->isHTML(true);
        $mail->Subject = "Novo contato: " . $data['about'];
        
        // Preparação do conteúdo usando template
        $bodyHtml = sprintf(
            "<p><strong>Nome:</strong> %s %s</p>
            <p><strong>E-mail:</strong> %s</p>
            <p><strong>Assunto:</strong> %s</p>
            <p><strong>Mensagem:</strong><br>%s</p>",
            $data['name'],
            $data['surname'],
            $data['email'],
            $data['about'],
            nl2br($data['message'])
        );
        
        $bodyText = sprintf(
            "Nome: %s %s\nE-mail: %s\nAssunto: %s\nMensagem:\n%s",
            $data['name'],
            $data['surname'],
            $data['email'],
            $data['about'],
            $data['message']
        );
        
        $mail->Body = $bodyHtml;
        $mail->AltBody = $bodyText;
        
        // Envia o e-mail
        $mail->send();
        
        // Limpa o buffer e redireciona
        if (ob_get_length()) ob_end_clean();
        header('Location: agradecimento.html');
        exit;
    } catch (Exception $e) {
        // Log do erro em um arquivo em vez de exibir na tela
        error_log('Erro de e-mail: ' . $mail->ErrorInfo, 0);
        echo ERROR_MESSAGE;
    }
}