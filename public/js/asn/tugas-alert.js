document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-tugas-asn");
    const modalAlert = document.getElementById("modal-alert");
    const btnBatal = document.getElementById("btn-batal");
    const btnLanjutkan = document.getElementById("btn-lanjutkan");

    // FIX: id yang benar adalah "penugasan_langsung" (checkbox-nya sendiri),
    // bukan "daftar-mahasiswa-langsung" (itu <div> container, tidak punya
    // properti .checked).
    const checkboxPenugasan = document.getElementById("penugasan_langsung");

    let skipCheck = false;

    if (form) {
        form.addEventListener("submit", async function (e) {
            if (skipCheck) {
                return;
            }

            if (checkboxPenugasan && checkboxPenugasan.checked) {
                e.preventDefault();

                const formData = new FormData(form);
                const mahasiswaIds = formData.getAll("mahasiswa_ids[]");

                if (mahasiswaIds.length === 0) {
                    skipCheck = true;
                    form.submit();
                    return;
                }

                try {
                    // FIX: URL disesuaikan dengan prefix route ASN. Kalau
                    // route Anda TIDAK di dalam prefix('asn'), hapus
                    // "/asn" di depan path ini.
                    const response = await fetch("/asn/tugas/check-aktif", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            Accept: "application/json",
                        },
                        body: JSON.stringify({ mahasiswa_ids: mahasiswaIds }),
                    });

                    if (!response.ok) {
                        // Endpoint 404/500 - jangan diam-diam lanjut submit,
                        // supaya kelihatan kalau ada yang salah saat development.
                        console.error(
                            "Endpoint check-aktif gagal, status:",
                            response.status,
                        );
                        skipCheck = true;
                        form.submit();
                        return;
                    }

                    const data = await response.json();

                    if (data.has_active_task) {
                        // Suntik daftar nama ke modal - pakai textContent
                        // (bukan innerHTML) supaya aman dari XSS kalau
                        // suatu saat ada nama mahasiswa yang mengandung
                        // karakter HTML.
                        const namaEl = document.getElementById(
                            "nama-mahasiswa-aktif",
                        );
                        if (namaEl) {
                            namaEl.textContent = data.nama_mahasiswa.join(", ");
                        }

                        modalAlert.classList.remove("hidden");
                        modalAlert.classList.add("flex");
                    } else {
                        skipCheck = true;
                        form.submit();
                    }
                } catch (error) {
                    console.error(
                        "Terjadi kesalahan saat mengecek data:",
                        error,
                    );
                    skipCheck = true;
                    form.submit();
                }
            }
        });
    }

    if (btnBatal) {
        btnBatal.addEventListener("click", function () {
            modalAlert.classList.add("hidden");
            modalAlert.classList.remove("flex");
        });
    }

    if (btnLanjutkan) {
        btnLanjutkan.addEventListener("click", function () {
            modalAlert.classList.add("hidden");
            modalAlert.classList.remove("flex");
            skipCheck = true;
            form.submit();
        });
    }
});
