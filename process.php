<?php

$method = $_SERVER["REQUEST_METHOD"];

if ($method !== "GET" && $method !== "POST") {
    header("Location: index.php");
    exit;
}

if ($method === "GET") {
    $source = $_GET;
} else {
    $source = $_POST;
}

$requestData = $_REQUEST;

$name = trim($source["name"] ?? "");
$email = trim($source["email"] ?? "");
$age = trim((string)($source["age"] ?? ""));
$gender = trim($source["gender"] ?? "");
$hobbies = $source["hobbies"] ?? array();
$comment = trim($source["comment"] ?? "");

if (!is_array($hobbies)) {
    $hobbies = array();
}

$allowedHobbies = array(
    "Reading", "Sports", "Music",
    "Traveling", "Gaming", "Cooking"
);

$hobbies = array_values(array_intersect(
    $hobbies,
    $allowedHobbies
));

$allowedGenders = array("Male", "Female");

$errors = array();

$bannedWords = array("bodoh", "tolol", "payah");

$letterCount = preg_match_all('/\p{L}/u', $name);

if ($name === "") {
    $errors["name"] = "Nama wajib diisi.";
} elseif ($letterCount < 3) {
    $errors["name"] = "Nama minimal terdiri dari 3 huruf.";
}

if ($email === "") {
    $errors["email"] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Format email tidak valid.";
}

if ($age === "") {
    $errors["age"] = "Umur wajib diisi.";
} elseif (!ctype_digit($age)) {
    $errors["age"] = "Umur harus berupa angka.";
} elseif ((int)$age < 17) {
    $errors["age"] = "Umur minimal 17 tahun.";
}

if (!in_array($gender, $allowedGenders, true)) {
    $errors["gender"] = "Silakan pilih gender.";
}

if (empty($hobbies)) {
    $errors["hobbies"] = "Silakan pilih minimal satu hobi.";
}

$words = preg_split(
    '/\s+/',
    $comment,
    -1,
    PREG_SPLIT_NO_EMPTY
);

$wordCount = count($words);

if ($comment === "") {
    $errors["comment"] = "Komentar wajib diisi.";
} elseif ($wordCount < 30) {
    $errors["comment"] = "Komentar minimal 30 kata.";
} else {
    foreach ($bannedWords as $word) {
        $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';

        if (preg_match($pattern, $comment)) {
            $errors["comment"] =
                "Komentar tidak boleh mengandung kata kasar.";
            break;
        }
    }
}

function showError($field, $errors) {
    if (isset($errors[$field])) {
        echo '<small class="error">'
            . htmlspecialchars($errors[$field])
            . '</small>';
    }
}

function safe($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<div class="form-card">

    <p class="eyebrow">PHP FORM HANDLING</p>

    <?php if (!empty($errors)) { ?>

        <h1>Validation Failed</h1>

        <p class="subtitle">
            Ada beberapa data yang perlu diperbaiki.
            Silakan periksa kembali form berikut.
        </p>

        <form action="process.php"
              method="<?php echo strtolower($method); ?>">

            <input type="hidden" name="submission_method"
                   value="<?php echo safe($method); ?>">

            <div class="form-group">
                <label>
                    Full Name <span class="required">*</span>
                </label>
                <input type="text" name="name"
                       value="<?php echo safe($name); ?>" required>
                <?php showError("name", $errors); ?>
            </div>

            <div class="form-group">
                <label>
                    Email Address <span class="required">*</span>
                </label>
                <input type="text" name="email"
                       value="<?php echo safe($email); ?>" required>
                <?php showError("email", $errors); ?>
            </div>

            <div class="form-group">
                <label>
                    Age <span class="required">*</span>
                </label>
                <input type="number" name="age"
                       value="<?php echo safe($age); ?>" required>
                <?php showError("age", $errors); ?>
            </div>

            <div class="form-group">
                <label>
                    Gender <span class="required">*</span>
                </label>

                <div class="option-group">
                    <label>
                        <input type="radio" name="gender"
                               value="Male"
                               <?php echo $gender === "Male" ? "checked" : ""; ?>
                               required>
                        Male
                    </label>

                    <label>
                        <input type="radio" name="gender"
                               value="Female"
                               <?php echo $gender === "Female" ? "checked" : ""; ?>>
                        Female
                    </label>
                </div>

                <?php showError("gender", $errors); ?>
            </div>

            <div class="form-group">
                <label>
                    Hobbies <span class="required">*</span>
                </label>

                <div class="option-group hobbies">
                    <?php foreach ($allowedHobbies as $hobby) { ?>
                        <label>
                            <input type="checkbox" name="hobbies[]"
                                   value="<?php echo safe($hobby); ?>"
                                   <?php echo in_array($hobby, $hobbies, true) ? "checked" : ""; ?>>
                            <?php echo safe($hobby); ?>
                        </label>
                    <?php } ?>
                </div>

                <?php showError("hobbies", $errors); ?>
            </div>

            <div class="form-group">
                <label>
                    Comment <span class="required">*</span>
                </label>

                <textarea name="comment" rows="5"
                          required><?php echo safe($comment); ?></textarea>

                <?php showError("comment", $errors); ?>
            </div>

            <button type="submit">Submit Again</button>

        </form>

    <?php } else { ?>

        <h1>Submission Successful!</h1>

        <p class="subtitle">
            Semua data berhasil melewati validasi.
        </p>

        <div class="success">
            Form berhasil dikirim dan divalidasi.
        </div>

        <div class="result">

            <p>
                <strong>Full Name:</strong><br>
                <?php echo safe($name); ?>
            </p>

            <p>
                <strong>Email Address:</strong><br>
                <?php echo safe($email); ?>
            </p>

            <p>
                <strong>Age:</strong><br>
                <?php echo safe($age); ?> tahun
            </p>

            <p>
                <strong>Gender:</strong><br>
                <?php echo safe($gender); ?>
            </p>

            <p>
                <strong>Hobbies:</strong><br>
                <?php echo safe(implode(", ", $hobbies)); ?>
            </p>

            <p>
                <strong>Comment:</strong><br>
                <?php echo nl2br(safe($comment)); ?>
            </p>

            <p>
                <strong>Jumlah kata komentar:</strong>
                <?php echo $wordCount; ?> kata
            </p>

        </div>

        <p>
            <strong>Metode pengiriman:</strong>
            <?php echo safe($method); ?>
        </p>

        <a href="index.php" class="button-link">
            Back to Form
        </a>

    <?php } ?>

    <hr>

    <h2>GET vs POST</h2>

    <p>
        <strong>GET:</strong>
        Mengirim data melalui URL sehingga data dapat terlihat
        pada address bar browser. GET biasanya digunakan
        untuk mengambil data.
    </p>

    <p>
        <strong>POST:</strong>
        Mengirim data melalui body request HTTP sehingga data
        tidak ditampilkan langsung pada URL. POST biasanya
        digunakan untuk mengirim data formulir.
    </p>

    <p>
        <strong>$_GET:</strong>
        Digunakan untuk mengambil data yang dikirim
        menggunakan metode GET.
    </p>

    <p>
        <strong>$_POST:</strong>
        Digunakan untuk mengambil data yang dikirim
        menggunakan metode POST.
    </p>

    <p>
        <strong>$_REQUEST:</strong>
        Digunakan untuk mengambil data request,
        termasuk GET dan POST sesuai konfigurasi PHP.
    </p>

    <p>
        <strong>Metode yang digunakan:</strong>
        <?php echo safe($method); ?>
    </p>

</div>
</div>

</body>
</html>

