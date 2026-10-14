<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/styles.css">
  <!-- Google-fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap"
    rel="stylesheet">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&display=swap"
    rel="stylesheet">

  <!-- ============================ -->

  <title>Maison Rosalie – Le livre de recettes au chocolat</title>
  <meta name="description"
    content="Maison Rosalie, chocolaterie belge depuis 1987 : découvrez nos recettes de chocolat pas à pas.">
  <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre&family=Josefin+Sans:wght@300;400&display=swap"
    rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
<!-- LEAFLET -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  
</head>

<body>
  <header class="header">
    <div class="container">

      <div class="logo-wrap">
        <a class="logo" href="?page=accueil"><img src="images/figma/logo-mr.png" alt="Maison Rosalie"></a>
      </div>

      <nav class="navbar">
        <div class="nav-top">
          <div class="nav-brand">
            <div class="login-wrap">
              <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-connected">

                  <span class="username">
                    <?= htmlspecialchars(ucfirst($_SESSION['username'])) ?>
                  </span>
                  <form method="post" action="?page=logout">
                    <button type="submit" class="logout-btn" aria-label="Déconnexion" title="Déconnexion">
                      <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m16 17 5-5-5-5" />
                        <path d="M21 12H9" />
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                      </svg>
                    </button>
                  </form>
                </div>
              <?php else: ?>
                <button class="login-open" type="button" aria-label="Connexion" aria-expanded="false"
                  aria-controls="login-panel">
                  <svg class="icon icon-line" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M19 11v6" />
                    <path d="M19 13h2" />
                    <path d="M2 21a8 8 0 0 1 12.868-6.349" />
                    <circle cx="10" cy="8" r="5" />
                    <circle cx="19" cy="19" r="2" />
                  </svg>
                </button>

                <!-- Panneau connexion -->
                <?php
                // message d'erreur de connexion (affiché une seule fois) : le panneau s'ouvre tout seul
                $loginError = $_SESSION['login_error'] ?? null;
                $loginEmailValue = $_SESSION['login_email'] ?? '';
                unset($_SESSION['login_error'], $_SESSION['login_email']);
                ?>
                <div class="login-panel<?= $loginError ? ' is-open' : '' ?>" id="login-panel">

                  <p class="login-title">Connexion</p>
                  <?php if ($loginError): ?>
                    <p class="login-error" role="alert"><?= htmlspecialchars($loginError) ?></p>
                  <?php endif; ?>
                  <form class="login-form" method="post" action="">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <label for="login-email">E-mail</label>
                    <input type="email" id="login-email" name="email" value="<?= htmlspecialchars($loginEmailValue) ?>"
                      required>
                    <label for="login-password">Mot de passe</label>
                    <input type="password" id="login-password" name="password" required>

                    <button class="login-submit" type="submit" name="login_submit" value="1">Login</button>
                  </form>

                  <div class="login-social">
                    <a href="#">
                      <img src="images/icons/google.svg" alt="" width="16" height="16">
                      Google
                    </a>
                    <a href="#">
                      <img src="images/icons/facebook.svg" alt="" width="16" height="16">
                      Facebook
                    </a>
                  </div>

                  <p class="login-or">ou</p>
                  <a class="login-register" href="?page=inscription">Crée un compte</a>
                </div>
              <?php endif; ?>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <?php $unreadCount = $unreadContactCount ?? 0; ?>
              <!-- Notifications admin : messages de contact non lus -->
              <a class="nav-notif" href="?pg=admin&amp;page=messages" title="Messages de contact"
                aria-label="Messages de contact<?= $unreadCount > 0 ? ' : ' . $unreadCount . ' non lu' . ($unreadCount > 1 ? 's' : '') : '' ?>">
                <svg class="icon icon-line" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" aria-hidden="true">
                  <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                  <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                </svg>
                <?php if ($unreadCount > 0): ?>
                  <span class="notif-badge" aria-hidden="true"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                <?php endif; ?>
              </a>
            <?php endif; ?>
            <a href="?page=recettes">
              <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                  d="M8 2h8a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3zm0 2a1 1 0 0 0-1 1v3h4V4H8zm5 0v4h4V5a1 1 0 0 0-1-1h-3zM7 10v4h4v-4H7zm6 0v4h4v-4h-4zM7 16v3a1 1 0 0 0 1 1h3v-4H7zm6 0v4h3a1 1 0 0 0 1-1v-3h-4z" />
              </svg>
            </a>
            <a class="nav-contact" href="?page=contact" aria-label="Contact" title="Contact" <?= ($page ?? '') === 'contact' ? ' aria-current="page"' : '' ?>>
              <svg class="icon icon-stroke" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                viewBox="0 0 29.33 29.33" aria-hidden="true">
                <rect x="2.67" y="6" width="24" height="17.33" rx="2.67" />
                <path d="m3.33 7.33 11.34 8.67 11.33-8.67" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </div>
        </div>
        <ul class="nav-list" id="main-menu">
          <li class="nav-item">
            <a class="nav-link" href="?page=accueil">Accueil</a>
          </li>

          <li class="nav-item"><a class="nav-link" href="?page=recettes">Recettes</a></li>

          <li class="nav-item"><a class="nav-link" href="?page=apropos">À propos</a></li>

        </ul>
      </nav>
    </div>
  </header>