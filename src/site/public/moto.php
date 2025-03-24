<?php
session_start();
require '../appel/pdo.php';
?>

<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="../assets/css/moto.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>TA HONDA</title>
</head>
<body>
    <?php include '../header/header.php'; ?>

    <div class="container">
        <!-- Sidebar pour filtres -->
        <aside class="sidebar">
            <h3>Filtres</h3>
            <form id="filterForm">
                <label>Rechercher :</label>
                <input type="text" name="search" placeholder="Nom du modèle">

                <label>Catégorie :</label>
                <select name="categorie">
                    <option value="">Toutes</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Cross">Cross</option>
                    <option value="Cruiser">Cruiser</option>
                    <option value="Enduro">Enduro</option>
                    <option value="Roadster">Roadster</option>
                    <option value="Routiere">Routiere</option>
                    <option value="Sportive">Sportive</option>
                    <option value="Supermotard">Supermotard</option> 
                    <option value="Trail">Trail</option>
                </select>

                <label>Année :</label>
                <select name="annee">
                    <option value="">Toutes</option>
                    <?php for ($i = 2025; $i >= 1950; $i--): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>

                <label>Trier par :</label>
                <select name="ordre">
                    <option value="id DESC">Récent</option>
                    <option value="Chevaux DESC">Puissance décroissante</option>
                    <option value="Chevaux ASC">Puissance croissante</option>
                    <option value="Cylindree DESC">Cylindrée décroissante</option>
                    <option value="Cylindree ASC">Cylindrée croissante</option>
                </select>

                <button type="submit">Filtrer</button>
            </form>
        </aside>

        <!-- Liste des motos et pagination -->
        <div class="moto-container" id="motoList"></div>
        <div id="pagination"></div>
    </div>

    <script>
        function loadMotos(page = 1) {
            $.ajax({
                url: 'moto_list.php',
                type: 'POST',
                data: $("#filterForm").serialize() + "&page=" + page,
                success: function(response) {
                    $("#motoList").html(response);
                }
            });
        }

        $(document).ready(function() {
            $("#filterForm").submit(function(event) {
                event.preventDefault();
                loadMotos(1);
            });

            $(document).on("click", ".pagination-link", function(event) {
                event.preventDefault();
                let page = $(this).data("page");
                loadMotos(page);
            });

            loadMotos(1);
        });
    </script>
</body>
</html>
