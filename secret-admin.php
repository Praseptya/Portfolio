<?php
session_start();
$jsonFile = 'data/data.json';
$message = '';

// Proses Login
if (isset($_POST['login'])) {
    if ($_POST['password'] === 'admin123') { // GANTI PASSWORD SEBELUM DI-HOSTING
        $_SESSION['is_admin'] = true;
    } else {
        $message = "Password salah!";
    }
}

// Proses Tambah Data jika sudah login
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    
    // Tambah Skill Baru (Dengan Kategori)
    if (isset($_POST['add_skill'])) {
        $category = $_POST['category'];
        $newSkill = htmlspecialchars($_POST['skill_name']);
        
        $currentData = json_decode(file_get_contents($jsonFile), true);
        
        // Memastikan kategori ada sebelum di-push
        if (!isset($currentData['skills'][$category])) {
            $currentData['skills'][$category] = [];
        }
        
        array_push($currentData['skills'][$category], $newSkill);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        $message = "Skill berhasil ditambahkan ke kategori $category!";
    }

    // Tambah Project Baru
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
    <title>ARYA. - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0a0f; 
            --accent-gold: #d4af37; 
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body { 
            background-color: var(--bg-dark); 
            color: #f9f9f9; 
            font-family: 'Poppins', sans-serif; 
            padding: 50px 20px; 
            display: flex; 
            justify-content: center; 
        }
        .admin-box { 
            background: var(--glass-bg); 
            backdrop-filter: blur(16px); 
            padding: 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 500px; 
            border: 1px solid var(--glass-border); 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        h2, h3 { color: #fff; margin-bottom: 20px; text-align: center; font-weight: 600; }
        h3 { font-size: 1.2rem; text-align: left; margin-top: 30px; margin-bottom: 15px; color: var(--accent-gold); }
        input, textarea, select, button { 
            width: 100%; 
            padding: 12px 15px; 
            margin-bottom: 15px; 
            border-radius: 8px; 
            border: 1px solid rgba(255,255,255,0.1); 
            background: rgba(0,0,0,0.2); 
            color: white; 
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }
        select option { background: var(--bg-dark); color: white; }
        button { 
            background: var(--accent-gold); 
            color: #000; 
            font-weight: 600; 
            cursor: pointer; 
            border: none; 
            transition: 0.3s; 
            margin-top: 5px;
        }
        button:hover { 
            background: #e8c961; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }
        .msg { 
            background: rgba(76, 175, 80, 0.1); 
            color: #4caf50; 
            padding: 10px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            border: 1px solid rgba(76, 175, 80, 0.3);
            text-align: center;
        }
        .error { color: #ff5252; text-align: center; margin-bottom: 15px; }
        .logout { 
            color: var(--text-secondary); 
            text-decoration: none; 
            display: block; 
            text-align: center; 
            margin-top: 30px; 
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .logout:hover { color: var(--accent-gold); }
    </style>
</head>
<body>

<div class="admin-box">
    <h2>Admin Panel</h2>
    
    <?php if($message && strpos($message, 'salah') !== false): ?> 
        <p class="error"><?= $message ?></p> 
    <?php elseif($message): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['is_admin'])): ?>
        <!-- Form Login -->
        <form method="POST">
            <label style="font-size: 0.9rem; color: #9ca3af; display: block; margin-bottom: 8px;">Password Akses</label>
            <input type="password" name="password" required placeholder="••••••••">
            <button type="submit" name="login">Masuk</button>
        </form>
    <?php else: ?>
        <!-- Form Tambah Skill -->
        <h3>Tambah Keahlian</h3>
        <form method="POST">
            <select name="category" required>
                <option value="Web & Backend">Web & Backend</option>
                <option value="Mobile & AI">Mobile & AI</option>
                <option value="Design & Tools">Design & Tools</option>
            </select>
            <input type="text" name="skill_name" placeholder="Nama Skill (contoh: React JS)" required>
            <button type="submit" name="add_skill">Simpan Keahlian</button>
        </form>
        
        <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

        <!-- Form Tambah Project -->
        <h3>Tambah Proyek Baru</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Judul Proyek" required>
            <textarea name="description" placeholder="Deskripsi singkat proyek" rows="3" required></textarea>
            <input type="url" name="link" placeholder="URL Proyek (contoh: GitHub link)" required>
            <input type="url" name="image" placeholder="URL Gambar (JPG/PNG)" required>
            <button type="submit" name="add_project">Simpan Proyek</button>
        </form>

        <a href="logout.php" class="logout">Keluar & Kembali ke Beranda &#8599;</a>
    <?php endif; ?>
</div>

</body>
</html>