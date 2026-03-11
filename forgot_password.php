<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mot de passe perdu - QuickTix</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <img src="logo.png" alt="QuickTix Logo" class="form-logo" />
        <h2>Mot de passe oublié</h2>
        <p class="subtitle-text">
          Entrez votre email pour réinitialiser votre mot de passe
        </p>
      </div>
      <form action="index.php" class="login-form">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="votre@email.com" required />
        </div>

        <button type="submit" class="login-button">Envoyer le lien</button>
      </form>
      <div class="login-footer">
        <p>
          <a href="index.php" class="text-link">Retour à la connexion</a>
        </p>
      </div>
    </div>
  </div>
  <script src="validation.js"></script>
</body>

</html>