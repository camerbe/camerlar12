<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur notre plateforme</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <tr>
        <td style="background-color: #111827; padding: 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px;">Bienvenue parmi nous !</h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px; color: #374151; line-height: 1.6;">
            <p style="margin-top: 0;">Bonjour <strong>{{ $prenom }} {{ $nom }}</strong>,</p>

            <p>Votre compte administrateur/rédacteur vient d'être créé avec succès sur notre plateforme.</p>

            <p>Voici vos identifiants de connexion temporaires pour accéder à votre espace :</p>

            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p style="margin: 0 0 10px 0;"><strong>E-mail :</strong> {{ $email }}</p>
                <p style="margin: 0;"><strong>Mot de passe temporaire :</strong> <code style="background: #e5e7eb; padding: 2px 6px; border-radius: 4px;">{{ $password }}</code></p>
            </div>

            <p style="color: #dc2626; font-size: 13px;"><em>Par mesure de sécurité, nous vous conseillons vivement de modifier votre mot de passe dès votre première connexion.</em></p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/login') }}" style="background-color: #2563eb; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Se connecter à mon espace</a>
            </div>
        </td>
    </tr>
    <tr>
        <td style="background-color: #f9fafb; padding: 20px; text-align: center; color: #9ca3af; font-size: 12px;">
            <p style="margin: 0;">Ceci est un e-mail automatique, merci de ne pas y répondre.</p>
        </td>
    </tr>
</table>
</body>
</html>
