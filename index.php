<!DOCTYPE html>
<html>
<head>
    <title>Formularz zwrotu towaru</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pobierz dane z formularza
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $email = $_POST['email'];
        $powod_zwrotu = $_POST['powod_zwrotu'];
        $numer_zamowienia = $_POST['numer_zamowienia'];

        // Inicjalizacja klasy TCPDF
        require_once 'tcpdf/tcpdf.php';
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

        // Ustawienia PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Twoje Imię');
        $pdf->SetTitle('Formularz zwrotu towaru');
        $pdf->SetMargins(15, 15, 15);

        // Dodaj nową stronę
        $pdf->AddPage();

        // Tworzenie zawartości PDF
        $html = '<h1>Formularz zwrotu towaru</h1>';
        $html .= '<p><strong>Imię:</strong> ' . $imie . '</p>';
        $html .= '<p><strong>Nazwisko:</strong> ' . $nazwisko . '</p>';
        $html .= '<p><strong>Email:</strong> ' . $email . '</p>';
        $html .= '<p><strong>Numer zamówienia:</strong> ' . $numer_zamowienia . '</p>';
        $html .= '<p><strong>Powód zwrotu:</strong> ' . $powod_zwrotu . '</p>';

        // Generowanie zawartości HTML do PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Generowanie pliku PDF
        $pdfData = $pdf->Output('', 'S'); // Pobierz dane PDF jako string

        // Inicjalizacja klasy PHPMailer
        require_once 'phpmailer/PHPMailerAutoload.php';
        $mailer = new PHPMailer\PHPMailer\PHPMailer();

        // Konfiguracja ustawień SMTP
        $mailer->isSMTP();
        $mailer->Host = 'smtp.example.com';
        $mailer->Port = 587;
        $mailer->SMTPAuth = true;
        $mailer->Username = 'your_username';
        $mailer->Password = 'your_password';

        // Adres nadawcy i odbiorcy
        $mailer->setFrom('your_email@example.com', 'Your Name');
        $mailer->addAddress($email, $imie . ' ' . $nazwisko);

        // Temat i treść wiadomości
        $mailer->Subject = 'Formularz zwrotu towaru';
        $mailer->Body = 'Witaj ' . $imie . ', w załączniku znajduje się Twój formularz zwrotu towaru.';
        $mailer->AltBody = 'Witaj ' . $imie . ', w załączniku znajduje się Twój formularz zwrotu towaru.';

        // Dodanie załącznika - plik PDF
        $mailer->addStringAttachment($pdfData, 'formularz_zwrotu.pdf');

        // Wysłanie wiadomości
        if ($mailer->send()) {
            echo 'Wiadomość e-mail z formularzem zwrotu towaru została wysłana pomyślnie.';
        } else {
            echo 'Wystąpił błąd podczas wysyłania wiadomości e-mail: ' . $mailer->ErrorInfo;
        }
    }
    ?>

    <h1>Formularz zwrotu towaru</h1>
    <form method="POST" action="">
        <label for="imie">Imię:</label>
        <input type="text" name="imie" id="imie" required>
        <br><br>
        <label for="nazwisko">Nazwisko:</label>
        <input type="text" name="nazwisko" id="nazwisko" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br><br>
        <label for="numer_zamowienia">Numer zamówienia:</label>
        <input type="text" name="numer_zamowienia" id="numer_zamowienia" required>
        <br><br>
        <label for="powod_zwrotu">Powód zwrotu:</label>
        <textarea name="powod_zwrotu" id="powod_zwrotu" rows="4" required></textarea>
        <br><br>
        <input type="submit" value="Generuj formularz zwrotu i wyślij na maila">
    </form>
</body>
</html>
