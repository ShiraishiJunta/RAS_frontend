<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tes Halaman Pop-up</title>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body>

    <h1 style="text-align: center; margin: 20px;">Halaman Tes Pop-up</h1>

    <button @click.prevent="$dispatch('logout-confirm')" style="display: block; margin: 50px auto; padding: 15px; font-size: 20px;">
        Tes Tombol Logout
    </button>

    <div
        x-data="{ show: false }"
        @logout-confirm.window="show = true"
        x-show="show"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;"
    >
        <div style="background: white; padding: 40px; border-radius: 10px; text-align: center;">
            <h2>Pop-up Berhasil Muncul!</h2>
            <button @click="show = false" style="margin-top: 20px; padding: 10px;">Tutup</button>
        </div>
    </div>

</body>
</html>