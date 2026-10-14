<?php
require_once __DIR__ . "/inc/header.php";
?>

<main class="contact">

    <section class="contact-intro">
        <div class="container">
            <h1 class="contact-title">Contacter Maison Rosalie</h1>
            <div class="contact-lead">
                <p class="contact-question">Une question, un projet<br>ou une envie gourmande ?</p>
                <p class="contact-text">Nous serons ravis de vous répondre et de partager avec vous l’univers de Maison
                    Rosalie</p>
            </div>
        </div>
    </section>

    <section class="contact-info">
        <div class="container">
            <ul class="contact-info-list">
                <li class="contact-info-item">
                    <svg class="contact-info-icon" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="2.5" y="5" width="19" height="14" rx="1.5" />
                        <path d="m3 6 9 7 9-7" />
                    </svg>
                    <a class="contact-info-link" href="mailto:contact@maisonrosalie.be">contact@maisonrosalie.be</a>
                </li>
                <li class="contact-info-item">
                    <svg class="contact-info-icon" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path
                            d="M5 3h4l2 5-2.5 1.5a11 11 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z" />
                    </svg>
                    <a class="contact-info-link" href="tel:+3221254567">+32 2 125 45 67</a>
                </li>
                <li class="contact-info-item">
                    <svg class="contact-info-icon" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z" />
                        <circle cx="12" cy="9.5" r="2.5" />
                    </svg>
                    <address class="contact-info-link">Rue des Pâtissiers 12<br>4000 Liège, Belgique</address>
                    </li>
            </ul>
        </div>
    </section>

    <section class="contact-form-section">
        <div class="container">
            <form class="contact-form" action="?page=contact" method="post">
                <h2 class="contact-form-title">Envoyez-nous un message</h2>

                <label class="sr-only" for="name" >Nom complet</label>
                <input class="contact-field" type="text" id="name" name="name" maxlength="100" placeholder="Nom complet *" required>

                <label class="sr-only" for="email" >Adresse e-mail</label>
                <input class="contact-field" type="email" id="email" name="email" maxlength="254" placeholder="Adresse e-mail *"
                    required>
                <label class="sr-only" for="subject" >Sujet</label>
                <input class="contact-field" type="text" id="subject" name="subject" maxlength="120" placeholder="Sujet *" required>

                <label class="sr-only" for="message">Votre message</label>
                <textarea class="contact-field contact-textarea" id="message" name="message"  maxlength="2000" rows="10"
                    placeholder="Votre message *" required></textarea>

                <button class="contact-submit" type="submit">
                    Envoyer le message
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M4 12h16m-6-6 6 6-6 6" />
                    </svg>
                </button>
            </form>
            <div class="contact-map">
                <div id="map"></div>
            </div>
        </div>
    </section>

</main>

<?php
require_once __DIR__ . "/inc/footer.php";
?>