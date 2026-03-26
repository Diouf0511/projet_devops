<?php
$conn = new mysqli("mysql", "root", "root", "app_db");

// Vérifier connexion
if ($conn->connect_error) {
    die("Erreur connexion: " . $conn->connect_error);
}

// Ajouter
if (isset($_POST['add'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    if (!empty($name) && !empty($email)) {
        $conn->query("INSERT INTO users (name, email) VALUES ('$name', '$email')");
    }
}

// Supprimer
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

// Récupérer utilisateur à modifier
$editUser = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $resultEdit = $conn->query("SELECT * FROM users WHERE id=$id");
    $editUser = $resultEdit->fetch_assoc();
}

// Modifier
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
}

// Liste
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD DevOps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2 class="mb-4">Gestion des utilisateurs 🚀</h2>

<!-- FORMULAIRE -->
<form method="POST" class="mb-4">
    <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

    <div class="mb-2">
        <input type="text" name="name" class="form-control"
        placeholder="Nom"
        value="<?= $editUser['name'] ?? '' ?>" required>
    </div>

    <div class="mb-2">
        <input type="email" name="email" class="form-control"
        placeholder="Email"
        value="<?= $editUser['email'] ?? '' ?>" required>
    </div>

    <?php if ($editUser): ?>
        <button class="btn btn-warning" name="update">Modifier</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    <?php else: ?>
        <button class="btn btn-primary" name="add">Ajouter</button>
    <?php endif; ?>
</form>

<!-- TABLEAU -->
<table class="table table-bordered">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td>
        <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
           onclick="return confirm('Supprimer ?')">Supprimer</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>