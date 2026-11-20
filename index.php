
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="form-card">

        <p class="eyebrow">PHP & FORM HANDLING</p>

        <h1>User Information Form</h1>
        <p class="subtitle">
            Silakan isi informasi berikut.
        </p>

        <p class="required-info">
            Kolom dengan tanda <span class="required">*</span>
            wajib diisi.
        </p>

        <form action="process.php" method="POST" id="userForm">

            <!-- Full Name -->
            <div class="form-group">
                <label for="name">
                    Full Name <span class="required">*</span>
                </label>
                <input type="text" id="name" name="name"
                       placeholder="Masukkan nama lengkap" required>
                <small>Minimal 3 huruf.</small>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">
                    Email Address <span class="required">*</span>
                </label>
                <input type="text" id="email" name="email"
                       placeholder="contoh@email.com" required>
                <small>Gunakan format email yang valid.</small>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">
                    Age <span class="required">*</span>
                </label>
                <input type="number" id="age" name="age"
                       placeholder="Minimal 17 tahun" min="17" required>
                <small>Umur harus berupa angka dan minimal 17 tahun.</small>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label>
                    Gender <span class="required">*</span>
                </label>

                <div class="option-group">
                    <label>
                        <input type="radio" name="gender"
                               value="Male" required>
                        Male
                    </label>

                    <label>
                        <input type="radio" name="gender"
                               value="Female">
                        Female
                    </label>
                </div>
            </div>

            <!-- Hobbies -->
            <div class="form-group">
                <label>
                    Hobbies <span class="required">*</span>
                </label>

                <div class="option-group hobbies">
                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Reading">
                        Reading
                    </label>

                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Sports">
                        Sports
                    </label>

                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Music">
                        Music
                    </label>

                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Traveling">
                        Traveling
                    </label>

                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Gaming">
                        Gaming
                    </label>

                    <label>
                        <input type="checkbox" name="hobbies[]"
                               value="Cooking">
                        Cooking
                    </label>
                </div>

                <small>Pilih minimal satu hobi.</small>
            </div>

            <!-- Comment -->
            <div class="form-group">
                <label for="comment">
                    Comment <span class="required">*</span>
                </label>

                <textarea id="comment" name="comment" rows="5"
                          placeholder="Tuliskan komentar minimal 30 kata..."
                          required></textarea>

                <small>
                    Minimal 30 kata dan tidak boleh mengandung kata kasar.
                </small>
            </div>

            <!-- Submission Method -->
            <div class="method-box">
                <label>
                    Metode Pengiriman Form
                </label>

                <div class="option-group">
                    <label>
                        <input type="radio" name="submission_method"
                               value="GET">
                        GET
                    </label>

                    <label>
                        <input type="radio" name="submission_method"
                               value="POST" checked>
                        POST
                    </label>
                </div>

                <small id="methodInfo">
                    POST: Data dikirim melalui body request HTTP
                    dan tidak ditampilkan langsung pada URL.
                </small>
            </div>

            <button type="submit">Submit</button>

        </form>
    </div>
</div>

<script>
    const form = document.getElementById("userForm");
    const methods = document.querySelectorAll(
        'input[name="submission_method"]'
    );
    const methodInfo = document.getElementById("methodInfo");

    methods.forEach(function(method) {
        method.addEventListener("change", function() {
            form.method = this.value.toLowerCase();

            if (this.value === "GET") {
                methodInfo.textContent =
                    "GET: Data dikirim melalui URL dan terlihat pada address bar browser.";
            } else {
                methodInfo.textContent =
                    "POST: Data dikirim melalui body request HTTP dan tidak ditampilkan langsung pada URL.";
            }
        });
    });

    form.addEventListener("submit", function(event) {
        const hobbies = document.querySelectorAll(
            'input[name="hobbies[]"]:checked'
        );

        if (hobbies.length === 0) {
            event.preventDefault();
            alert("Silakan pilih minimal satu hobi.");
        }
    });
</script>

</body>
</html>

