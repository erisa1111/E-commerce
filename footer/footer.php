<?php 

// Avoid starting the session again if it's already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connect.php';

?>
<footer class="footer">
    <div class="footer-column">
        <h3>NDIHMË</h3>
        <a href="#">Rreth nesh</a>
        <a href="#">Kostot e Transportit dhe Koha e Dorëzimit</a>
        <a href="#">Faturimi, anulimi, dhe porositë e modifikuara</a>
        <a href="#">Politika e Kthimit</a>
        <a href="#">Kushtet e përgjithshme</a>
        <a href="#">Shërbimi ndaj klientit</a>
        <a href="#">Forum</a>
    </div>
    <div class="footer-column">
        <h3>DYQANET</h3>
        <a href="#">Albi Mall kati 0</a>
        <a href="#">Albi Mall kati 1</a>
        <a href="#">Bregu i Diellit</a>
        <a href="#">Royal Mall Rruga B</a>
        <a href="#">Galeria Prizren</a>
        <a href="#">Rr. Nëna Terezë</a>
        <a href="#">Rr. Garibaldi përballë Grandit</a>
        <a href="#">The Village - Shopping & Fun, Ferizaj</a>
    </div>
    <div class="footer-column">
        <h3>NA NDIQNI NË</h3>
        <div class="social-icons">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">LinkedIn</a>
        </div>
        <h3>KONTAKT</h3>
        <p style="font-size: 13px; color: #aaa;">Tel: <a href="tel:+38349747100" id="phone-number">+38349747100</a></p>
        <p style="font-size: 13px; color: #aaa;">Email: <a href="mailto:erisamerjeme@gmail.com" id="email-color">erisamerjeme@gmail.com</a></p>
        <div class="subscribe">
            <input type="email" placeholder="Email address">
            <button>ABONOHU</button>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-divider"></div>
        <p>All rights reserved &copy; Erisa Matoshi & Merjeme Bajrami</p>
    </div>
</footer>

<?php
// Footer styles or scripts can also be included here if needed
?>
