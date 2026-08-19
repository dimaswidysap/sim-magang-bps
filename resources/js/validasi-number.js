const inputAngkaElements = document.querySelectorAll(".hanya-angka");

inputAngkaElements.forEach(function (inputElement) {
    inputElement.addEventListener("input", function (e) {
        // Hapus karakter yang bukan angka
        this.value = this.value.replace(/[^0-9]/g, "");
    });
});
