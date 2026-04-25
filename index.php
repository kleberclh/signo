<?php
$pageTitle = "Consulta de Signo";
include("layouts/header.php");
?>
<section class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <article class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-3 text-center">Descubra seu signo</h1>
                <p class="text-muted text-center mb-4">
                    Informe sua data de nascimento para consultar seu signo zodiacal.
                </p>

                <form id="signo-form" method="POST" action="show_zodiac_sign.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="data_nascimento" class="form-label">Data de nascimento</label>
                        <input
                            type="date"
                            class="form-control"
                            id="data_nascimento"
                            name="data_nascimento"
                            required
                        >
                        <div class="invalid-feedback">
                            Informe uma data de nascimento valida.
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Consultar signo
                        </button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</section>

<script>
    (function () {
        "use strict";
        var forms = document.querySelectorAll(".needs-validation");
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener("submit", function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            }, false);
        });
    })();
</script>

</div>
</main>
</body>
</html>
