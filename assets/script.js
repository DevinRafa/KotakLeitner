// KotakLeitner - JavaScript sederhana (tanpa library)

// 1. Mode belajar: balik kartu, lalu tampilkan tombol benar/salah
var kartu = document.getElementById('kartu3d');
if (kartu) {
    var tombolBalik = document.getElementById('tombolBalik');
    var tombolJawab = document.getElementById('tombolJawab');

    tombolBalik.addEventListener('click', function () {
        kartu.classList.add('flipped');
        tombolBalik.classList.add('hidden');
        tombolJawab.classList.remove('hidden');
    });
}

// 2. Pilihan warna deck / avatar: tandai pilihan yang aktif
var pilihan = document.querySelectorAll('.pilih-opsi');
for (var i = 0; i < pilihan.length; i++) {
    pilihan[i].addEventListener('change', function () {
        var kelompok = document.querySelectorAll('input[name="' + this.name + '"]');
        for (var j = 0; j < kelompok.length; j++) {
            var label = kelompok[j].parentNode;
            if (kelompok[j].checked) {
                label.classList.add('on');
            } else {
                label.classList.remove('on');
            }
        }
    });
}
