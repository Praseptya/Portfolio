<?php
session_start();
$jsonFile = 'data/data.json';
$message = '';

// Proses Login
if (isset($_POST['login'])) {
    if ($_POST['password'] === 'admin123') { // GANTI PASSWORD DI SINI SEBELUM DI-HOSTING
        $_SESSION['is_admin'] = true;
    } else {
        $message = "Password salah!";
    }
}

// Proses Tambah Data jika sudah login
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    if (isset($_POST['add_skill'])) {
        $newSkill = htmlspecialchars($_POST['skill_name']);
        
        $currentData = json_decode(file_get_contents($jsonFile), true);
        array_push($currentData['skills'], $newSkill);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        $message = "Skill berhasil ditambahkan!";
    }

    if (isset($_POST['add_project'])) {
        $newProject = [
            'title' => htmlspecialchars($_POST['title']),
            'description' => htmlspecialchars($_POST['description']),
            'link' => htmlspecialchars($_POST['link']),
            'image' => htmlspecialchars($_POST['image'])
        ];
        
        $currentData = json_decode(file_get_contents($jsonFile), true);
        array_push($currentData['projects'], $newProject);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        $message = "Project berhasil ditambahkan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #e0e0e0; font-family: 'Poppins', sans-serif; padding: 50px; }
        .admin-box { background: #1e1e1e; padding: 30px; border-radius: 8px; max-width: 500px; margin: 0 auto; border: 1px solid #333; }
        input, textarea, button { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #444; background: #2a2a2a; color: white; }
        button { background: #d4af37; color: #121212; font-weight: bold; cursor: pointer; border: none; }
        button:hover { background: #b8962c; }
        .msg { color: #4caf50; margin-bottom: 15px; }
        .logout { color: #d4af37; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="admin-box">
    <h2>Admin Panel Rahasia</h2>
    <?php if($message): ?> <p class="msg"><?= $message ?></p> <?php endif; ?>

    <?php if (!isset($_SESSION['is_admin'])): ?>
        <!-- Form Login -->
        <form method="POST">
            <label>Password Admin:</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
    <?php else: ?>
        <!-- Form Tambah Skill -->
        <h3>Tambah Skill Baru</h3>
        <form method="POST">
            <input type="text" name="skill_name" placeholder="Nama Skill (misal: React JS)" required>
            <button type="submit" name="add_skill">Simpan Skill</button>
        </form>
        
        <hr style="border-color:#333; margin:30px 0;">

        <!-- Form Tambah Project -->
        <h3>Tambah Project Baru</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Judul Project" required>
            <textarea name="description" placeholder="Deskripsi singkat" rows="3" required></textarea>
            <input type="url" name="link" placeholder="Link URL Project" required>
            <input type="url" name="image" placeholder="Link URL Gambar (JPG/PNG)" required>
            <button type="submit" name="add_project">Simpan Project</button>
        </form>

        <a href="logout.php" class="logout">Logout & Kembali ke Utama</a>
    <?php endif; ?>
</div>

</body>
</html>