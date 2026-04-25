<?php
$pageTitle = "Resultado do Signo";
include("layouts/header.php");

function parseXmlDateToMonthDay(string $xmlDate): ?string
{
    $parts = explode("/", $xmlDate);
    if (count($parts) !== 2) {
        return null;
    }

    $day = (int) trim($parts[0]);
    $month = (int) trim($parts[1]);

    if (!checkdate($month, $day, 2001)) {
        return null;
    }

    return sprintf("%02d-%02d", $month, $day);
}

function findSignByBirthDate(DateTime $birthDate, SimpleXMLElement $signs): ?SimpleXMLElement
{
    $birthMonthDay = $birthDate->format("m-d");

    foreach ($signs->signo as $sign) {
        $start = parseXmlDateToMonthDay((string) $sign->dataInicio);
        $end = parseXmlDateToMonthDay((string) $sign->dataFim);

        if ($start === null || $end === null) {
            continue;
        }

        if ($start <= $end) {
            if ($birthMonthDay >= $start && $birthMonthDay <= $end) {
                return $sign;
            }
            continue;
        }

        if ($birthMonthDay >= $start || $birthMonthDay <= $end) {
            return $sign;
        }
    }

    return null;
}

$message = null;
$selectedSign = null;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $message = "A consulta deve ser feita pelo formulario da pagina inicial.";
} else {
    $dateInput = $_POST["data_nascimento"] ?? "";
    $birthDate = DateTime::createFromFormat("Y-m-d", $dateInput);
    $isBirthDateValid = $birthDate instanceof DateTime && $birthDate->format("Y-m-d") === $dateInput;

    if (!$isBirthDateValid) {
        $message = "Data de nascimento invalida. Tente novamente.";
    } else {
        $xmlFile = __DIR__ . "/signos.xml";
        $signs = @simplexml_load_file($xmlFile);

        if ($signs === false) {
            $message = "Nao foi possivel carregar os dados dos signos.";
        } else {
            $selectedSign = findSignByBirthDate($birthDate, $signs);
            if ($selectedSign === null) {
                $message = "Nao foi possivel determinar o signo para a data informada.";
            }
        }
    }
}
?>

<section class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <article class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-4 text-center">Resultado da consulta</h1>

                <?php if ($selectedSign !== null): ?>
                    <div class="result-pill mb-3">
                        <span class="text-muted d-block mb-1">Seu signo e:</span>
                        <strong class="fs-3"><?php echo htmlspecialchars((string) $selectedSign->signoNome, ENT_QUOTES, "UTF-8"); ?></strong>
                    </div>

                    <p class="mb-2">
                        <strong>Periodo:</strong>
                        <?php echo htmlspecialchars((string) $selectedSign->dataInicio, ENT_QUOTES, "UTF-8"); ?>
                        a
                        <?php echo htmlspecialchars((string) $selectedSign->dataFim, ENT_QUOTES, "UTF-8"); ?>
                    </p>
                    <p class="mb-0">
                        <?php echo htmlspecialchars((string) $selectedSign->descricao, ENT_QUOTES, "UTF-8"); ?>
                    </p>
                <?php else: ?>
                    <div class="alert alert-warning mb-0" role="alert">
                        <?php echo htmlspecialchars((string) $message, ENT_QUOTES, "UTF-8"); ?>
                    </div>
                <?php endif; ?>

                <div class="mt-4 d-grid d-md-flex justify-content-md-center">
                    <a href="index.php" class="btn btn-outline-primary">Voltar</a>
                </div>
            </div>
        </article>
    </div>
</section>

</div>
</main>
</body>
</html>
