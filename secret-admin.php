<?php
session_start();
$jsonFile = 'data/data.json';
$uploadDir = 'assets/'; // Folder penyimpanan gambar
$message = '';

// Proses Login
if (isset($_POST['login'])) {
    if ($_POST['password'] === 'admin123') { // Ganti password sebelum di-hosting
        $_SESSION['is_admin'] = true;
    } else {
        $message = "Password salah!";
    }
}

// Proses Tambah Data jika sudah login
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    
    // 1. Tambah Skill Baru
    if (isset($_POST['add_skill'])) {
        $category = $_POST['category'];
        $newSkill = htmlspecialchars($_POST['skill_name']);
        
        $currentData = json_decode(file_get_contents($jsonFile), true);
        
        if (!isset($currentData['skills'][$category])) {
            $currentData['skills'][$category] = [];
        }
        
        array_push($currentData['skills'][$category], $newSkill);
        file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));
        $message = "Skill berhasil ditambahkan ke kategori $category!";
    }

    // 2. Tambah Project Baru (Dengan Upload Gambar)
    if (isset($_POST['add_project'])) {
        $title = htmlspecialchars($_POST['title']);
        $shortDesc = htmlspecialchars($_POST['short_desc']);
        $role = htmlspecialchars($_POST['role']);
        $description = htmlspecialchars($_POST['description']);
        $year = htmlspecialchars($_POST['year']);
        $link = htmlspecialchars($_POST['link']);
        
        // Memproses Tech Stack (dipisahkan koma)
        $techStackInput = $_POST['tech_stack'];
        $techStackArray = array_map('trim', explode(',', $techStackInput));

        // Membuat inisial otomatis dari judul (Misal: "Program Budgeting" -> "PB")
        $words = explode(" ", $title);
        $initial = "";
        foreach ($words as $w) {
            $initial .= strtoupper($w[0] ?? '');
        }

        // Proses File Upload Gambar
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Format Nama File sesuai Judul Proyek (lowercase & mengganti spasi/karakter khusus dengan strip)
            $slugTitle = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $newFileName = $slugTitle . '-full.' . $fileExtension;
            $targetFilePath = $uploadDir . $newFileName;

            // Pindahkan file ke folder assets/
            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                
                $newProject = [
                    'title' => $title,
                    'initial' => $initial,
                    'short_desc' => $shortDesc,
                    'role' => $role,
                    'description' => $description,
                    'year' => $year,
                    'tech_stack' => $techStackArray,
                    'image_file' => $targetFilePath, // Path file lokal otomatis
                    'link' => $link
                ];

                $currentData = json_decode(file_get_contents($jsonFile), true);
                array_push($currentData['projects'], $newProject);
                file_put_contents($jsonFile, json_encode($currentData, JSON_PRETTY_PRINT));

                $message = "Proyek berhasil ditambahkan & gambar tersimpan sebagai '$newFileName'!";
            } else {
                $message = "Gagal mengunggah file gambar ke folder assets/. Check permission folder.";
            }
        } else {
            $message = "Pilih file gambar proyek terlebih dahulu!";
        }
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
            max-width: 550px; 
            border: 1px solid var(--glass-border); 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        h2, h3 { color: #fff; margin-bottom: 20px; text-align: center; font-weight: 600; }
        h3 { font-size: 1.2rem; text-align: left; margin-top: 30px; margin-bottom: 15px; color: var(--accent-gold); }
        label { display: block; font-size: 0.85rem; color: #9ca3af; margin-bottom: 5px; text-align: left; }
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
        input[type="file"] { padding: 8px; background: rgba(255,255,255,0.05); cursor: pointer; }
        select option { background: var(--bg-dark); color: white; }
        button { 
            background: var(--accent-gold); 
            color: #000; 
            font-weight: 600; 
            cursor: pointer; 
            border: none; 
            transition: 0.3s; 
            margin-top: 10px;
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
            font-size: 0.9rem;
        }
        .error { color: #ff5252; text-align: center; margin-bottom: 15px; font-size: 0.9rem; }
        .logout { 
            color: #9ca3af; 
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
    
    <?php if($message && (strpos($message, 'salah') !== false || strpos($message, 'Gagal') !== false)): ?> 
        <p class="error"><?= $message ?></p> 
    <?php elseif($message): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['is_admin'])): ?>
        <!-- Form Login -->
        <form method="POST">
            <label>Password Akses</label>
            <input type="password" name="password" required placeholder="••••••••">
            <button type="submit" name="login">Masuk</button>
        </form>
    <?php else: ?>
        <!-- Form Tambah Skill -->
        <h3>Tambah Keahlian</h3>
        <form method="POST">
            <label>Kategori Keahlian</label>
            <select name="category" required>
                <option value="Web & Backend">Web & Backend</option>
                <option value="Mobile & AI">Mobile & AI</option>
                <option value="Design & Tools">Design & Tools</option>
            </select>
            <label>Nama Keahlian</label>
            <input type="text" name="skill_name" placeholder="Contoh: React JS" required>
            <button type="submit" name="add_skill">Simpan Keahlian</button>
        </form>
        
        <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

        <!-- Form Tambah Project (Dengan File Upload) -->
        <h3>Tambah Proyek Baru</h3>
        <form method="POST" enctype="multipart/form-data">
            <label>Judul Proyek</label>
            <input type="text" name="title" placeholder="Contoh: Program Budgeting System" required>
            
            <label>Deskripsi Singkat (Aksen Emas)</label>
            <input type="text" name="short_desc" placeholder="Contoh: Manajemen anggaran produksi televisi" required>
            
            <label>Peran / Role</label>
            <input type="text" name="role" placeholder="Contoh: Full-Stack Developer" required>
            
            <label>Tahun Proyek</label>
            <input type="text" name="year" placeholder="Contoh: 2025" required>
            
            <label>Deskripsi Lengkap</label>
            <textarea name="description" placeholder="Deskripsi rinci mengenai proyek..." rows="3" required></textarea>
            
            <label>Tech Stack (Pisahkan dengan koma)</label>
            <input type="text" name="tech_stack" placeholder="Contoh: PHP, Laravel, MySQL, Figma" required>
            
            <label>File Gambar Proyek (Akan disimpan ke assets/)</label>
            <input type="file" name="image_file" accept="image/*" required>
            
            <label>URL Source Code (GitHub / External Link)</label>
            <input type="url" name="link" placeholder="https://github.com/..." required>
            
            <button type="submit" name="add_project">Simpan Proyek</button>
        </form>

        <a href="logout.php" class="logout">Keluar & Kembali ke Beranda &#8599;</a>
    <?php endif; ?>
</div>

</body>
</html>