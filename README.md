<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

<!--  -->

  <!-- SECTION 2: Data Mahasiswa -->

                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        Data Mahasiswa
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">NIM / NIS</label>
                            <input type="text" name="nim" value="{{ old('nim') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Instansi Asal
                                (Kampus/Sekolah)</label>
                            <input type="text" name="instansi_asal" value="{{ old('instansi_asal') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jenjang</label>
                            <select name="jenjang"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                <option value="">-- Pilih Jenjang --</option>
                                <option value="SMA/SMK" {{ old('jenjang') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                                <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Periode & Tanggal Magang -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Periode & Waktu Magang
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-background p-5 rounded-xl border border-border">
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Pilih Periode</label>
                            <select name="periode_magang_id"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                <option value="">-- Pilih Periode --</option>
                                @foreach ($periodeList as $periode)
                                    <option value="{{ $periode->id }}"
                                        {{ old('periode_magang_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nama_periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-light mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                class="w-full px-4 py-2.5 bg-surface border border-border rounded-lg text-text focus:outline-none focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Skill / Label -->
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Skill / Label
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($skillList as $skill)
                            <label for="skill-{{ $skill->id }}"
                                class="flex items-center gap-2 px-4 py-2 bg-background border border-border rounded-full cursor-pointer hover:border-primary transition-colors">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                    id="skill-{{ $skill->id }}"
                                    {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary bg-surface border-border rounded focus:ring-primary focus:ring-2 cursor-pointer accent-primary">
                                <span class="text-sm font-medium text-text">{{ $skill->nama_skill }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
