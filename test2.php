<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Kayak Trip</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      background: url('/src/img/photo_kayak.png') no-repeat center center fixed;
      background-size: cover;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      padding-top: 6rem;
      color: white;
      z-index: 1000;
      backdrop-filter: blur(8px);
    }

    .sidebar a {
      display: block;
      padding: 1rem 2rem;
      color: white;
      text-decoration: none;
      font-weight: 600;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: rgba(255, 255, 255, 0.1);
      border-left: 4px solid #00E18C;
    }

    .main-content {
      margin-left: 260px;
      padding: 3rem 2rem;
      color: white;
      background-color: rgba(0, 0, 0, 0.6);
      min-height: 100vh;
    }

    .section {
      margin-bottom: 4rem;
    }

    h2 {
      border-bottom: 2px solid #00E18C;
      padding-bottom: 0.5rem;
      margin-bottom: 1.5rem;
    }

    .form-control, .form-select {
      background-color: rgba(255,255,255,0.9);
    }

    .btn {
      border-radius: 2rem;
    }

    canvas {
      background: white;
      border-radius: 0.5rem;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center fw-bold mb-4">Admin Kayak Trip</h4>
    <a href="#etapes" class="active"><i class="bi bi-geo-alt"></i> Points d’arrêt</a>
    <a href="#hebergements"><i class="bi bi-house-door"></i> Hébergements</a>
    <a href="#promos"><i class="bi bi-percent"></i> Promotions</a>
    <a href="#services"><i class="bi bi-tools"></i> Services</a>
    <a href="#tarifs"><i class="bi bi-calendar2-week"></i> Tarifs saisonniers</a>
    <a href="#stats"><i class="bi bi-bar-chart-line"></i> Taux d’occupation</a>
    <a href="#messagerie"><i class="bi bi-envelope"></i> Messagerie</a>
    <a href="#newsletter"><i class="bi bi-newspaper"></i> Newsletter</a>
  </div>

  <!-- Contenu principal -->
  <div class="main-content">

    <h1 class="text-center mb-5">Panneau d’administration</h1>

    <!-- Étapes -->
    <div id="etapes" class="section">
      <h2>Points d’arrêt</h2>
      <form class="row g-3">
        <div class="col-md-6">
          <label>Nom de l’étape</label>
          <input type="text" class="form-control">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <button class="btn btn-success">Ajouter</button>
        </div>
      </form>
    </div>

    <!-- Hébergements -->
    <div id="hebergements" class="section">
      <h2>Hébergements</h2>
      <form class="row g-3">
        <div class="col-md-4">
          <label>Point d’arrêt</label>
          <select class="form-select">
            <option>Choisir...</option>
          </select>
        </div>
        <div class="col-md-4">
          <label>Nom</label>
          <input type="text" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-success">Ajouter</button>
        </div>
      </form>
      <hr class="text-white">
      <p>Modifier les périodes de fermeture pour travaux ou maintenance.</p>
    </div>

    <!-- Promotions -->
    <div id="promos" class="section">
      <h2>Promotions</h2>
      <form class="row g-3">
        <div class="col-md-4">
          <label>Période</label>
          <input type="date" class="form-control">
        </div>
        <div class="col-md-4">
          <label>Remise (%)</label>
          <input type="number" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-success">Appliquer</button>
        </div>
      </form>
      <div class="mt-3">
        <p><strong>Code promo première réservation :</strong> <code>KAYAK2025</code></p>
      </div>
    </div>

    <!-- Services -->
    <div id="services" class="section">
      <h2>Services complémentaires</h2>
      <form class="row g-3">
        <div class="col-md-6">
          <label>Nom du service</label>
          <input type="text" class="form-control">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <button class="btn btn-success">Ajouter</button>
        </div>
      </form>
    </div>

    <!-- Tarifs -->
    <div id="tarifs" class="section">
      <h2>Tarification saisonnière</h2>
      <form class="row g-3">
        <div class="col-md-4">
          <label>Du</label>
          <input type="date" class="form-control">
        </div>
        <div class="col-md-4">
          <label>Au</label>
          <input type="date" class="form-control">
        </div>
        <div class="col-md-4">
          <label>Tarif (€)</label>
          <input type="number" class="form-control">
        </div>
        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-success">Appliquer</button>
        </div>
      </form>
    </div>

    <!-- Statistiques -->
    <div id="stats" class="section">
      <h2>Taux d’occupation</h2>
      <canvas id="chartOccupation" height="100"></canvas>
    </div>

    <!-- Messagerie -->
    <div id="messagerie" class="section">
      <h2>Messagerie commerciale</h2>
      <p>Module de messages clients à intégrer ici (filtrage, réponses, etc.)</p>
    </div>

    <!-- Newsletter -->
    <div id="newsletter" class="section">
      <h2>Newsletter</h2>
      <form class="d-flex gap-3">
        <input type="email" class="form-control" placeholder="Adresse e-mail">
        <button class="btn btn-primary">Ajouter</button>
      </form>
    </div>
  </div>

  <!-- Graphique -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('chartOccupation').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        datasets: [{
          label: 'Taux d’occupation (%)',
          data: [58, 72, 85, 90, 78, 94, 69],
          backgroundColor: '#00E18C'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true, max: 100 }
        }
      }
    });
  </script>

</body>
</html>
