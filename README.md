# Moby Facilities - Documentação do Projeto

## Visão Geral

O site **Moby Facilities** foi desenvolvido com o objetivo de ser simples, eficiente e de alta performance. Ele utiliza apenas **HTML**, **CSS** e **JavaScript** para a interface do usuário, enquanto o envio do formulário de contato é gerenciado por um script em **PHP**. Essa abordagem foi escolhida para garantir um site leve, rápido e fácil de manter, sem a necessidade de frameworks ou bibliotecas externas.

---

## Estrutura do Projeto

O projeto é composto pelos seguintes arquivos principais:

- **`index.html`**: Página inicial do site.
- **`contact.html`**: Página de contato com o formulário.
- **`styles.css`**: Arquivo de estilos para toda a aplicação.
- **`script.js`**: Arquivo JavaScript para interatividade e animações.
- **`send_email.php`**: Script PHP responsável pelo envio do formulário.
- **`agradecimento.html`**: Página de agradecimento exibida após o envio bem-sucedido do formulário.

---

## Envio do Formulário de Contato

O envio do formulário na página de contato foi implementado utilizando **PHP** puro, sem frameworks, para manter a simplicidade e a performance do site. Abaixo, explicamos como o processo foi realizado:

### 1. **Estrutura do Formulário**

O formulário está localizado na página `contact.html` e utiliza o método `POST` para enviar os dados ao script `send_email.php`. Ele inclui campos como nome, sobrenome, email, assunto e mensagem. O campo de email possui validação básica com o atributo `pattern` para garantir que o formato seja válido.

```html
<form class="form-container" action="send_email.php" method="POST">
    <div class="form-row">
        <div class="form-field">
            <label for="name">Nome</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-field">
            <label for="surname">Sobrenome</label>
            <input type="text" name="surname" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="email">Email *</label>
            <input type="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Por favor, insira um email válido.">
        </div>
        <div class="form-field">
            <label for="about">Assunto</label>
            <input type="text" name="about" required>
        </div>
    </div>
    <label for="message">Mensagem</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn-enviar">Enviar</button>
</form>
```
```markdown
# Moby Facilities - Documentação do Projeto

## Visão Geral

O site **Moby Facilities** foi desenvolvido com o objetivo de ser simples, eficiente e de alta performance. Ele utiliza apenas **HTML**, **CSS** e **JavaScript** para a interface do usuário, enquanto o envio do formulário de contato é gerenciado por um script em **PHP**. Essa abordagem foi escolhida para garantir um site leve, rápido e fácil de manter, sem a necessidade de frameworks ou bibliotecas externas.

---

## Estrutura do Projeto

O projeto é composto pelos seguintes arquivos principais:

- **`index.html`**: Página inicial do site.
- **`contact.html`**: Página de contato com o formulário.
- **`styles.css`**: Arquivo de estilos para toda a aplicação.
- **`script.js`**: Arquivo JavaScript para interatividade e animações.
- **`send_email.php`**: Script PHP responsável pelo envio do formulário.
- **`agradecimento.html`**: Página de agradecimento exibida após o envio bem-sucedido do formulário.

---

## Envio do Formulário de Contato

O envio do formulário na página de contato foi implementado utilizando **PHP** puro, sem frameworks, para manter a simplicidade e a performance do site. Abaixo, explicamos como o processo foi realizado:

### 1. **Estrutura do Formulário**

O formulário está localizado na página `contact.html` e utiliza o método `POST` para enviar os dados ao script `send_email.php`. Ele inclui campos como nome, sobrenome, email, assunto e mensagem. O campo de email possui validação básica com o atributo `pattern` para garantir que o formato seja válido.
```
```html
<form class="form-container" action="send_email.php" method="POST">
    <div class="form-row">
        <div class="form-field">
            <label for="name">Nome</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-field">
            <label for="surname">Sobrenome</label>
            <input type="text" name="surname" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-field">
            <label for="email">Email *</label>
            <input type="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Por favor, insira um email válido.">
        </div>
        <div class="form-field">
            <label for="about">Assunto</label>
            <input type="text" name="about" required>
        </div>
    </div>
    <label for="message">Mensagem</label>
    <textarea name="message" required></textarea>
    <button type="submit" class="btn-enviar">Enviar</button>
</form>
```

---

### 2. **Processamento do Formulário com PHP**

O script `send_email.php` é responsável por processar os dados enviados pelo formulário e enviá-los por email. Abaixo estão os principais passos realizados:

#### a) **Validação do Método de Requisição**

O script verifica se o método de requisição é `POST` antes de processar os dados:

```php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processa os dados do formulário
} else {
    echo 'Método de requisição inválido.';
}
```

#### b) **Sanitização e Validação dos Dados**

Os dados enviados pelo formulário são filtrados e sanitizados para evitar ataques como **XSS** e **SQL Injection**. O email é validado para garantir que esteja no formato correto:

```php
$formData = filter_input_array(INPUT_POST, [
    'name' => FILTER_SANITIZE_SPECIAL_CHARS,
    'surname' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_SANITIZE_EMAIL,
    'about' => FILTER_SANITIZE_SPECIAL_CHARS,
    'message' => FILTER_SANITIZE_SPECIAL_CHARS
]);

if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
    die('E-mail inválido.');
}
```

#### c) **Envio do Email**

O envio do email é realizado utilizando a biblioteca **PHPMailer**. As configurações do servidor SMTP, como host, porta, usuário e senha, são carregadas de um arquivo `.env` para maior segurança:

```php
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
$mail->isSMTP();
$mail->Host = $_ENV['MAIL_HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['MAIL_USERNAME'];
$mail->Password = $_ENV['MAIL_PASSWORD'];
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = $_ENV['MAIL_PORT'];
```

O conteúdo do email é configurado em formato HTML e texto simples (para compatibilidade com diferentes clientes de email):

```php
$mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
$mail->addAddress($_ENV['MAIL_TO']);
$mail->addReplyTo($formData['email'], $formData['name'] . ' ' . $formData['surname']);

$mail->isHTML(true);
$mail->Subject = "Novo contato: " . $formData['about'];
$mail->Body = sprintf(
    "<p><strong>Nome:</strong> %s %s</p>
    <p><strong>E-mail:</strong> %s</p>
    <p><strong>Assunto:</strong> %s</p>
    <p><strong>Mensagem:</strong><br>%s</p>",
    $formData['name'],
    $formData['surname'],
    $formData['email'],
    $formData['about'],
    nl2br($formData['message'])
);
$mail->AltBody = sprintf(
    "Nome: %s %s\nE-mail: %s\nAssunto: %s\nMensagem:\n%s",
    $formData['name'],
    $formData['surname'],
    $formData['email'],
    $formData['about'],
    $formData['message']
);
```

#### d) **Redirecionamento Após o Envio**

Após o envio bem-sucedido, o usuário é redirecionado para uma página de agradecimento:

```php
header('Location: agradecimento.html');
exit;
```

---

## Por Que Escolhemos HTML, CSS, JS e PHP?

1. **Simplicidade**: O uso de tecnologias básicas garante que o site seja fácil de entender, modificar e manter.
2. **Performance**: Sem frameworks ou bibliotecas pesadas, o site carrega rapidamente, mesmo em conexões lentas.
3. **Compatibilidade**: HTML, CSS, JS e PHP são amplamente suportados por navegadores e servidores.
4. **Custo**: Não há necessidade de servidores especializados ou configurações complexas.

---

## Como Executar o Projeto

1. **Requisitos**:
   - Servidor com suporte a PHP (ex.: Apache ou Nginx).
   - Biblioteca **PHPMailer** instalada via Composer.
   - Arquivo `.env` configurado com as credenciais do servidor SMTP.

2. **Passos**:
   - Clone o repositório para o servidor.
   - Configure o arquivo `.env` com as seguintes variáveis:
     ```
     MAIL_HOST=smtp.seuprovedor.com
     MAIL_USERNAME=seuemail@dominio.com
     MAIL_PASSWORD=suasenha
     MAIL_PORT=587
     MAIL_FROM=seuemail@dominio.com
     MAIL_FROM_NAME="Moby Facilities"
     MAIL_TO=destinatario@dominio.com
     ```
   - Certifique-se de que o servidor está configurado para processar arquivos PHP.
   - Acesse o site no navegador e teste o formulário de contato.

---

## Conclusão

O site **Moby Facilities** foi projetado para ser funcional e eficiente, utilizando tecnologias básicas para atender às necessidades do cliente. A implementação do formulário de contato com PHP garante uma solução robusta e segura para comunicação com os usuários.



