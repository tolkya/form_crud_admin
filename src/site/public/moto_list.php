<?php
session_start();
require '../appel/pdo.php';

$limit = 9; // Nombre de motos par page
$page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $limit;

$categorie = isset($_POST['categorie']) ? trim($_POST['categorie']) : '';
$annee = isset($_POST['annee']) ? (int)$_POST['annee'] : 0;
$ordre = isset($_POST['ordre']) ? $_POST['ordre'] : 'id DESC';
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$ordreAutorise = ['id DESC', 'Chevaux DESC', 'Chevaux ASC', 'Cylindree DESC', 'Cylindree ASC'];
if (!in_array($ordre, $ordreAutorise)) {
    $ordre = 'id DESC';
}

$sql = "SELECT SQL_CALC_FOUND_ROWS motos.*, users.username FROM motos 
        JOIN users ON motos.user_id = users.id WHERE 1=1";
$parametres = [];

if (!empty($categorie)) {
    $sql .= " AND motos.Categorie = :categorie";
    $parametres[':categorie'] = $categorie;
}
if ($annee > 0) {
    $sql .= " AND motos.Annee = :annee";
    $parametres[':annee'] = $annee;
}
if (!empty($search)) {
    $sql .= " AND motos.Modele LIKE :search";
    $parametres[':search'] = "%$search%";
}

$sql .= " ORDER BY $ordre LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($parametres as $key => $value) {
    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$motos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPages = ceil($total / $limit);
?>

<ul>
    <?php if ($motos): ?>
        <?php foreach ($motos as $moto): ?>
            <li class="moto-card">
                <h2><?php echo htmlspecialchars($moto['Modele']); ?> <br> <?php echo htmlspecialchars($moto['Annee']); ?></h2>
                <span class="proprietaire">👤 <?php echo htmlspecialchars($moto['username']); ?></span>
                <a href="moto_details.php?id=<?php echo $moto['id']; ?>">
                    <img src="../uploads/<?php echo htmlspecialchars($moto['Image']); ?>" alt="Moto">
                </a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune moto trouvée.</p>
    <?php endif; ?>
</ul>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="#" class="pagination-link" data-page="<?php echo $page - 1; ?>">Précédent</a>
    <?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="#" class="pagination-link" data-page="<?php echo $page + 1; ?>">Suivant</a>
    <?php endif; ?>
</div>
