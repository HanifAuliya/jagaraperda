<form id="formAspirasi">
    {{-- Identitas --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Nama *</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" />
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Alamat</label>
        <input type="text" name="alamat" class="form-control" placeholder="Alamat domisili (opsional)" />
    </div>

    <div class="row g-3">
        <div class="col-md-12">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" placeholder="nama@contoh.id"
                autocomplete="email" />
        </div>
    </div>

    {{-- Raperda --}}
    <div class="mb-3 mt-3">
        <label class="form-label fw-semibold">Raperda yang Dipilih</label>
        <select class="form-select select-blue" id="raperdaSelect" name="raperda_id" required>
            <option value="" selected disabled>Pilih Raperda</option>
            <option value="administrasi">Peraturan administrasi</option>
            <option value="fasilitas-publik">Fasilitas Publik</option>
            <option value="pelayanan">Pengelolaan sampah</option>
            <option value="lainnya">Lainnya</option>
        </select>
    </div>

    {{-- Isi --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Judul Masukkan *</label>
        <input type="text" name="judul" class="form-control" placeholder="Ringkas, jelas (maks. 150 karakter)"
            required />
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Isi Masukkan *</label>
        <textarea rows="4" name="isi" class="form-control" placeholder="Uraikan masukan/pertimbangan Anda..."
            required></textarea>
    </div>

    {{-- Lampiran --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Lampiran</label>
        <input type="file" name="lampiran[]" class="form-control" multiple />
        <small class="text-muted">PDF/JPG/PNG (maks 10 MB per file)</small>
    </div>

    {{-- Mode Privasi --}}
    <div class="d-flex align-items-center justify-content-between mt-4">
        <div class="d-flex flex-wrap gap-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mode_privasi" id="normal" value="normal"
                    checked />
                <label class="form-check-label" for="normal" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Nama Anda tidak akan terpublikasi pada laporan">Normal</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mode_privasi" id="anonim" value="anonim" />
                <label class="form-check-label" for="anonim" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Identitas tidak diminta, laporan tetap bisa diproses">Anonim</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mode_privasi" id="rahasia" value="rahasia" />
                <label class="form-check-label" for="rahasia" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Identitas hanya diketahui admin, tidak ditampilkan di publik">Rahasia</label>
            </div>
        </div>

        <button type="submit" class="btn btn-royal fw-bold px-4">
            LAPOR!
        </button>
    </div>

    {{-- Consent --}}
    <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" id="setuju" required />
        <label class="form-check-label" for="setuju">
            Saya setuju dengan ketentuan dan kebijakan privasi.
        </label>
    </div>
</form>
