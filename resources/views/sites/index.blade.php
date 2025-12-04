<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Offres d'emploi</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0; padding: 20px;
      background: #f4f4f4;
    }
    h1 { text-align: center; color: #333; }
    .offres {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .card {
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card h2 {
      font-size: 18px;
      margin: 0 0 10px;
      color: #0073e6;
    }
    .card p { margin: 0 0 10px; }
    .btn {
      display: inline-block;
      padding: 8px 15px;
      background: #0073e6;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
    }
    .btn:hover { background: #005bb5; }
  </style>
</head>
<body>
  <h1>Liste des offres d'emploi</h1>
  <div class="offres" id="offres"></div>

  <script>
    // Exemple de données
    const offres = [
      {id: 1, titre: "Développeur Web", lieu: "Abidjan", description: "Développement de sites et applications web."},
      {id: 2, titre: "Chargé de Communication", lieu: "Yamoussoukro", description: "Gestion de la communication interne et externe."},
      {id: 3, titre: "Comptable", lieu: "Bouaké", description: "Suivi des comptes et bilans financiers."},
    ];

    const container = document.getElementById('offres');

    offres.forEach(offre => {
      const card = document.createElement('div');
      card.className = "card";
      card.innerHTML = `
        <h2>${offre.titre}</h2>
        <p><strong>Lieu :</strong> ${offre.lieu}</p>
        <p>${offre.description.substring(0, 50)}...</p>
        <a href="offre.html?id=${offre.id}" class="btn">Voir</a>
      `;
      container.appendChild(card);
    });
  </script>
</body>
</html>
