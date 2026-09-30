<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Loan;
use App\Models\Publisher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $pustakawanRole = Role::firstOrCreate(['name' => 'pustakawan', 'guard_name' => 'web']);
        $dosenRole = Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $tamuRole = Role::firstOrCreate(['name' => 'tamu', 'guard_name' => 'web']);

        // 2. Users for each role
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@digitech.ac.id'],
            [
                'name' => 'Bima Administrator',
                'password' => Hash::make('password'),
                'nim_nidn' => 'ADM-SYS-01',
                'faculty' => 'Biro Sistem Informasi & Teknologi',
                'major' => 'Super Administrator',
                'max_borrow_quota' => 99,
            ]
        );
        $adminUser->syncRoles([$adminRole]);

        $pustakawanUser = User::updateOrCreate(
            ['email' => 'pustakawan@digitech.ac.id'],
            [
                'name' => 'Siti Rahmawati, S.Sos.',
                'password' => Hash::make('password'),
                'nim_nidn' => 'NIP: 19880415201201',
                'faculty' => 'UPT Perpustakaan Terpadu',
                'major' => 'Kepala Layanan Sirkulasi',
                'max_borrow_quota' => 99,
            ]
        );
        $pustakawanUser->syncRoles([$pustakawanRole]);

        $dosenUser = User::updateOrCreate(
            ['email' => 'dosen@digitech.ac.id'],
            [
                'name' => 'Dr. Hendra Gunawan, M.T.',
                'password' => Hash::make('password'),
                'nim_nidn' => 'NIDN: 0412097801',
                'faculty' => 'Fakultas Ilmu Komputer',
                'major' => 'Teknik Informatika (S1)',
                'max_borrow_quota' => 15,
            ]
        );
        $dosenUser->syncRoles([$dosenRole]);

        $mahasiswaUser = User::updateOrCreate(
            ['email' => 'mahasiswa@digitech.ac.id'],
            [
                'name' => 'Rafi Pratama',
                'password' => Hash::make('password'),
                'nim_nidn' => 'NIM: 22010884',
                'faculty' => 'Fakultas Ilmu Komputer',
                'major' => 'Teknik Informatika (S1)',
                'max_borrow_quota' => 5,
            ]
        );
        $mahasiswaUser->syncRoles([$mahasiswaRole]);

        // 3. Categories
        $categoriesData = [
            ['name' => 'Teknologi Informasi & AI', 'slug' => 'ti', 'icon' => 'Cpu', 'description' => 'Koleksi rekayasa perangkat lunak, arsitektur AI, dan sistem cerdas.'],
            ['name' => 'Bisnis Digital & FinTech', 'slug' => 'bisnis', 'icon' => 'TrendingUp', 'description' => 'Manajemen startup digital, e-commerce, dan inovasi finansial.'],
            ['name' => 'Desain Komunikasi Visual & UI/UX', 'slug' => 'dkv', 'icon' => 'Palette', 'description' => 'Desain antarmuka modern, interaksi manusia-komputer, dan multimedia.'],
            ['name' => 'Sains Data & Analitika', 'slug' => 'data', 'icon' => 'BarChart3', 'description' => 'Machine learning, big data analytics, dan visualisasi data statistik.'],
            ['name' => 'Keamanan Siber & Jaringan', 'slug' => 'jaringan', 'icon' => 'ShieldCheck', 'description' => 'Penetration testing, kriptografi, dan infrastruktur cloud terdistribusi.'],
            ['name' => 'Karya Ilmiah & Jurnal Kampus', 'slug' => 'umum', 'icon' => 'GraduationCap', 'description' => 'Publikasi open-access, prosiding, dan skripsi sivitas akademika.'],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $cat) {
            $categoryModels[$cat['slug']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Publishers
        $pubDigitech = Publisher::updateOrCreate(
            ['slug' => 'digitech-university-press'],
            ['name' => 'Digitech University Press', 'address' => 'Gedung Rektorat Lt. 2, Bandung', 'website' => 'https://press.digitech.ac.id']
        );
        $pubKreatif = Publisher::updateOrCreate(
            ['slug' => 'pustaka-kreatif-digitech'],
            ['name' => 'Pustaka Kreatif Digitech', 'address' => 'Laboratorium Multimedia Kampus 2', 'website' => 'https://kreatif.digitech.ac.id']
        );
        $pubInformatika = Publisher::updateOrCreate(
            ['slug' => 'informatika-pratama'],
            ['name' => 'Informatika Pratama Press', 'address' => 'Jl. Buah Batu No. 44, Bandung', 'website' => 'https://informatikapratama.id']
        );
        $pubLPPM = Publisher::updateOrCreate(
            ['slug' => 'lppm-digitech-university'],
            ['name' => 'LPPM Digitech University', 'address' => 'Gedung Riset & Inovasi Lt. 3', 'website' => 'https://lppm.digitech.ac.id']
        );
        $pubCyber = Publisher::updateOrCreate(
            ['slug' => 'cyber-tech-media'],
            ['name' => 'Cyber Tech Media', 'address' => 'Cyber Building Jakarta', 'website' => 'https://cybertechmedia.co.id']
        );

        // 5. Authors
        $authHendra = Author::updateOrCreate(['slug' => 'prof-dr-hendra-gunawan'], ['name' => 'Prof. Dr. Hendra Gunawan, S.Kom., M.T.']);
        $authAyu = Author::updateOrCreate(['slug' => 'ayu-lestari-m-ds'], ['name' => 'Ayu Lestari, M.Ds. & Tim Lab Multimedia']);
        $authRian = Author::updateOrCreate(['slug' => 'dr-ir-rian-setiawan'], ['name' => 'Dr. Ir. Rian Setiawan, S.E., M.M.']);
        $authBambang = Author::updateOrCreate(['slug' => 'bambang-wicaksono-m-t'], ['name' => 'Bambang Wicaksono, M.T. & Teguh Prasetyo, S.Kom.']);
        $authFadhil = Author::updateOrCreate(['slug' => 'fadhil-rahman-m-sc'], ['name' => 'Fadhil Rahman, S.Si., M.Sc. & Dinda Permata, M.Kom.']);
        $authSatria = Author::updateOrCreate(['slug' => 'satria-dewa-ceh'], ['name' => 'Satria Dewa, S.Kom., CEH, CISSP']);
        $authLPPM = Author::updateOrCreate(['slug' => 'dewan-redaksi-lppm'], ['name' => 'Dewan Redaksi LPPM Digitech University']);
        $authRizki = Author::updateOrCreate(['slug' => 'muhammad-rizki-m-kom'], ['name' => 'Muhammad Rizki, S.Kom., M.Kom.']);

        // 6. Books List
        $booksData = [
            [
                'title' => 'Arsitektur Kecerdasan Buatan Modern: Deep Learning & Large Language Models',
                'slug' => 'arsitektur-kecerdasan-buatan-modern-deep-learning-llm',
                'isbn' => '978-623-8812-40-1',
                'category_id' => $categoryModels['ti']->id,
                'publisher_id' => $pubDigitech->id,
                'publication_year' => 2025,
                'language' => 'Bahasa Indonesia',
                'pages' => 486,
                'synopsis' => 'Buku rujukan utama di lingkungan Digitech University yang mengupas tuntas evolusi kecerdasan buatan dari algoritma transformer, tokenization, optimasi parameter, hingga fine-tuning model LLM untuk kebutuhan industri masa kini. Dilengkapi studi kasus nyata implementasi PyTorch dan HuggingFace.',
                'cover_gradient' => 'from-blue-700 via-indigo-800 to-blue-950',
                'cover_color' => '#1E4FA3',
                'is_physical' => false,
                'is_digital' => true,
                'format_type' => 'pdf',
                'shelf_location' => 'Digital Repository Server 01',
                'total_stock' => 999,
                'available_stock' => 999,
                'borrow_count' => 820,
                'rating' => 4.90,
                'rating_count' => 142,
                'tags' => ['Artificial Intelligence', 'LLM', 'Python', 'PyTorch'],
                'authors' => [$authHendra->id],
                'ebook' => [
                    'file_size' => '14.2 MB',
                    'format' => 'pdf',
                    'sample_content' => [
                        'BAB 1: Fondasi Deep Learning dan Arsitektur Transformer',
                        'Dalam satu dekade terakhir, kecerdasan buatan telah melompat dari sistem berbasis heuristik menjadi arsitektur generasi terbaru yang memanfaatkan self-attention mechanism...',
                        'Perkembangan transformer pertama kali diperkenalkan oleh Vaswani et al. (2017) melalui konsep "Attention is All You Need", yang mengeliminasi kebutuhan recurrence (RNN) dan membuka jalan bagi paralelisasi komputasi masif pada GPU modern.',
                        'Di Digitech University, implementasi model ini telah diaplikasikan pada laboratorium pengenalan bahasa alami dan otomasi sistem layanan akademik cerdas...'
                    ]
                ]
            ],
            [
                'title' => 'Desain Antarmuka Pengguna & Claymorphism: Fondasi UI/UX Interaktif',
                'slug' => 'desain-antarmuka-pengguna-claymorphism-ui-ux',
                'isbn' => '978-623-7744-19-8',
                'category_id' => $categoryModels['dkv']->id,
                'publisher_id' => $pubKreatif->id,
                'publication_year' => 2024,
                'language' => 'Bahasa Indonesia',
                'pages' => 320,
                'synopsis' => 'Panduan komprehensif bagi desainer antarmuka masa depan. Membahas psikologi warna, hierarki visual, micro-interaction, dan implementasi gaya desain modern seperti Neumorphism, Glassmorphism, dan Claymorphism dalam produk web dan mobile berstandar internasional.',
                'cover_gradient' => 'from-rose-600 via-red-600 to-rose-900',
                'cover_color' => '#D92B3E',
                'is_physical' => false,
                'is_digital' => true,
                'format_type' => 'epub',
                'shelf_location' => 'Digital Repository Server 02',
                'total_stock' => 999,
                'available_stock' => 999,
                'borrow_count' => 654,
                'rating' => 4.85,
                'rating_count' => 96,
                'tags' => ['UI/UX', 'Claymorphism', 'Figma', 'Web Design'],
                'authors' => [$authAyu->id],
                'ebook' => [
                    'file_size' => '8.7 MB',
                    'format' => 'epub',
                    'sample_content' => [
                        'BAB 1: Eksplorasi Dimensi Visual dan Claymorphism',
                        'Claymorphism menawarkan sentuhan ramah dan taktil yang menjembatani kehangatan dunia fisik dengan presisi antarmuka digital. Karakteristik utamanya terletak pada border-radius besar, dual soft shadow luar, dan inner highlight yang lembut...',
                        'Tidak seperti skeuomorphism yang kompleks atau flat design yang terkadang terasa kaku, claymorphism memberikan kedalaman visual yang menyenangkan tanpa mengorbankan performa render di perangkat mobile.'
                    ]
                ]
            ],
            [
                'title' => 'Strategi Transformasi Bisnis Digital & Model Startup Berkelanjutan',
                'slug' => 'strategi-transformasi-bisnis-digital-startup',
                'isbn' => '978-602-5120-88-2',
                'category_id' => $categoryModels['bisnis']->id,
                'publisher_id' => $pubDigitech->id,
                'publication_year' => 2024,
                'language' => 'Bahasa Indonesia',
                'pages' => 412,
                'synopsis' => 'Membahas peta jalan lengkap membangun bisnis rintisan teknologi yang adaptif. Mulai dari validasi problem-solution fit, perancangan unit economics, model monetization freemium/SaaS, hingga tata kelola pendanaan ventura dan integrasi ekosistem pembayaran digital di Indonesia.',
                'cover_gradient' => 'from-emerald-600 via-teal-700 to-emerald-900',
                'cover_color' => '#059669',
                'is_physical' => true,
                'is_digital' => false,
                'format_type' => 'physical',
                'shelf_location' => 'Lantai 2 - Rak B-04 (Bisnis & Manajemen)',
                'total_stock' => 5,
                'available_stock' => 2,
                'borrow_count' => 310,
                'rating' => 4.75,
                'rating_count' => 78,
                'tags' => ['Startup', 'Digital Business', 'FinTech', 'Manajemen'],
                'authors' => [$authRian->id],
                'copies' => ['B-04-001', 'B-04-002', 'B-04-003', 'B-04-004', 'B-04-005']
            ],
            [
                'title' => 'Rekayasa Perangkat Lunak Skala Besar: Microservices & Event-Driven Architecture',
                'slug' => 'rekayasa-perangkat-lunak-skala-besar-microservices',
                'isbn' => '978-623-9932-15-4',
                'category_id' => $categoryModels['ti']->id,
                'publisher_id' => $pubInformatika->id,
                'publication_year' => 2025,
                'language' => 'Bahasa Indonesia',
                'pages' => 560,
                'synopsis' => 'Bedah arsitektur backend modern menggunakan Laravel, Go, Docker, Apache Kafka, dan Redis untuk menangani ribuan transaksi per detik. Sangat cocok untuk mahasiswa tingkat akhir dan praktisi yang ingin memahami arsitektur cloud-native yang kokoh dan fault-tolerant.',
                'cover_gradient' => 'from-blue-600 via-cyan-700 to-slate-900',
                'cover_color' => '#1E4FA3',
                'is_physical' => true,
                'is_digital' => true,
                'format_type' => 'hybrid',
                'shelf_location' => 'Lantai 3 - Rak A-12 (Teknologi & Rekayasa)',
                'total_stock' => 8,
                'available_stock' => 4,
                'borrow_count' => 940,
                'rating' => 4.92,
                'rating_count' => 189,
                'tags' => ['Software Engineering', 'Microservices', 'Laravel', 'Docker'],
                'authors' => [$authBambang->id],
                'copies' => ['A-12-001', 'A-12-002', 'A-12-003', 'A-12-004'],
                'ebook' => [
                    'file_size' => '22.4 MB',
                    'format' => 'pdf',
                    'sample_content' => [
                        'BAB 1: Transisi dari Monolith Menuju Arsitektur Terdistribusi',
                        'Membangun sistem yang siap melayani jutaan pengguna memerlukan perubahan paradigma: dari database terpusat tunggal menuju domain-driven design dengan komunikasi event-driven...'
                    ]
                ]
            ],
            [
                'title' => 'Praktikum Sains Data & Pembelajaran Mesin dengan Python: Hands-on Real Case',
                'slug' => 'praktikum-sains-data-machine-learning-python',
                'isbn' => '978-623-8812-55-5',
                'category_id' => $categoryModels['data']->id,
                'publisher_id' => $pubDigitech->id,
                'publication_year' => 2024,
                'language' => 'Bahasa Indonesia',
                'pages' => 430,
                'synopsis' => 'Panduan berbasis proyek langsung untuk memahami manipulasi data dengan Pandas, visualisasi data interaktif, rekayasa fitur (feature engineering), hingga deployment model machine learning menggunakan Scikit-Learn dan Streamlit.',
                'cover_gradient' => 'from-purple-700 via-indigo-800 to-slate-900',
                'cover_color' => '#7C3AED',
                'is_physical' => false,
                'is_digital' => true,
                'format_type' => 'pdf',
                'shelf_location' => 'Digital Repository Server 01',
                'total_stock' => 999,
                'available_stock' => 999,
                'borrow_count' => 712,
                'rating' => 4.88,
                'rating_count' => 115,
                'tags' => ['Data Science', 'Machine Learning', 'Python', 'Pandas'],
                'authors' => [$authFadhil->id],
                'ebook' => [
                    'file_size' => '18.1 MB',
                    'format' => 'pdf',
                    'sample_content' => [
                        'BAB 1: Eksplorasi Data & Pembersihan Dataset Nyata Kampus',
                        'Dataset di dunia nyata hampir selalu tidak sempurna: missing values, outliers, dan inkonsistensi tipe data adalah tantangan pertama seorang data scientist...'
                    ]
                ]
            ],
            [
                'title' => 'Keamanan Siber & Pertahanan Jaringan: Dari Teori ke Penetration Testing Praktis',
                'slug' => 'keamanan-siber-pertahanan-jaringan-penetration-testing',
                'isbn' => '978-602-9901-72-9',
                'category_id' => $categoryModels['jaringan']->id,
                'publisher_id' => $pubCyber->id,
                'publication_year' => 2024,
                'language' => 'Bahasa Indonesia',
                'pages' => 388,
                'synopsis' => 'Membongkar teknik vulnerability scanning, eksploitasi web (SQLi, XSS, CSRF), kriptografi praktis, hingga pengerasan sistem Linux dan arsitektur Zero Trust di lingkungan enterprise perguruan tinggi.',
                'cover_gradient' => 'from-red-700 via-slate-800 to-neutral-900',
                'cover_color' => '#D92B3E',
                'is_physical' => true,
                'is_digital' => false,
                'format_type' => 'physical',
                'shelf_location' => 'Lantai 3 - Rak C-02 (Jaringan & Siber)',
                'total_stock' => 4,
                'available_stock' => 1,
                'borrow_count' => 420,
                'rating' => 4.82,
                'rating_count' => 64,
                'tags' => ['Cyber Security', 'Pentest', 'Networking', 'Linux'],
                'authors' => [$authSatria->id],
                'copies' => ['C-02-001', 'C-02-002', 'C-02-003', 'C-02-004']
            ],
            [
                'title' => 'Jurnal Ilmiah Teknologi & Rekayasa Komputer (JTRK) Vol. 12 No. 2',
                'slug' => 'jurnal-ilmiah-teknologi-rekayasa-komputer-vol-12-no-2',
                'isbn' => 'ISSN: 2548-8921',
                'category_id' => $categoryModels['umum']->id,
                'publisher_id' => $pubLPPM->id,
                'publication_year' => 2025,
                'language' => 'Bahasa Indonesia & English',
                'pages' => 180,
                'synopsis' => 'Kumpulan artikel riset peer-reviewed dosen dan mahasiswa berprestasi Digitech University, mencakup topik algoritma kompresi data, IoT untuk smart campus, blockchain untuk validasi ijazah digital, dan sistem rekomendasi perpustakaan cerdas.',
                'cover_gradient' => 'from-blue-900 via-slate-800 to-indigo-950',
                'cover_color' => '#1E4FA3',
                'is_physical' => false,
                'is_digital' => true,
                'format_type' => 'pdf',
                'shelf_location' => 'Open Access Journal Repository',
                'total_stock' => 999,
                'available_stock' => 999,
                'borrow_count' => 530,
                'rating' => 4.70,
                'rating_count' => 42,
                'tags' => ['Jurnal', 'SINTA 2', 'Penelitian Dosen', 'Open Access'],
                'authors' => [$authLPPM->id],
                'ebook' => [
                    'file_size' => '6.4 MB',
                    'format' => 'pdf',
                    'sample_content' => [
                        'Artikel 1: Rancang Bangun Sistem Verifikasi Ijazah Berbasis Konsensus Blockchain di Lingkungan Perguruan Tinggi',
                        'Oleh: Dr. Ir. Hendra & Tim Peneliti Fakultas Ilmu Komputer Digitech University...'
                    ]
                ]
            ],
            [
                'title' => 'Pemrograman Web Modern: Vue.js 3, Inertia.js & REST API dengan Laravel',
                'slug' => 'pemrograman-web-modern-vue-3-laravel-rest-api',
                'isbn' => '978-623-8812-99-9',
                'category_id' => $categoryModels['ti']->id,
                'publisher_id' => $pubDigitech->id,
                'publication_year' => 2025,
                'language' => 'Bahasa Indonesia',
                'pages' => 512,
                'synopsis' => 'Kupas tuntas arsitektur Single Page Application (SPA), state management modern Pinia, Tailwind CSS untuk styling dinamis, dan autentikasi token Sanctum di Laravel. Menjadi modul pegangan wajib praktikum pemrograman web lanjutan.',
                'cover_gradient' => 'from-red-600 via-rose-700 to-indigo-950',
                'cover_color' => '#D92B3E',
                'is_physical' => false,
                'is_digital' => true,
                'format_type' => 'epub',
                'shelf_location' => 'Digital Repository Server 02',
                'total_stock' => 999,
                'available_stock' => 999,
                'borrow_count' => 1120,
                'rating' => 4.95,
                'rating_count' => 230,
                'tags' => ['Vue.js', 'Laravel', 'REST API', 'Pinia'],
                'authors' => [$authRizki->id],
                'ebook' => [
                    'file_size' => '11.5 MB',
                    'format' => 'epub',
                    'sample_content' => [
                        'BAB 1: Evolusi Antarmuka Web dan Single Page Application',
                        'Pengalaman pengguna (user experience) pada aplikasi web modern dituntut untuk secepat dan seringan aplikasi native. Kombinasi Vue 3 Composition API dengan backend Laravel menyediakan ekosistem produktif...'
                    ]
                ]
            ],
        ];

        $createdBooks = [];
        foreach ($booksData as $data) {
            $authors = $data['authors'] ?? [];
            $copies = $data['copies'] ?? [];
            $ebookData = $data['ebook'] ?? null;
            unset($data['authors'], $data['copies'], $data['ebook']);

            $book = Book::updateOrCreate(['slug' => $data['slug']], $data);
            $book->authors()->sync($authors);
            $createdBooks[] = $book;

            // Copies
            foreach ($copies as $copyCode) {
                BookCopy::updateOrCreate(
                    ['copy_code' => $copyCode],
                    ['book_id' => $book->id, 'condition' => 'good', 'status' => 'available']
                );
            }

            // Ebook
            if ($ebookData) {
                Ebook::updateOrCreate(
                    ['book_id' => $book->id],
                    [
                        'file_path' => 'ebooks/' . $book->slug . '.' . $ebookData['format'],
                        'file_size' => $ebookData['file_size'],
                        'format' => $ebookData['format'],
                        'sample_content' => $ebookData['sample_content'],
                        'drm_watermark_enabled' => true,
                    ]
                );
            }
        }

        // 7. Seed Initial Loans for Mahasiswa User
        $startupBook = Book::where('slug', 'strategi-transformasi-bisnis-digital-startup')->first();
        $copy = BookCopy::where('book_id', $startupBook->id)->first();
        if ($copy) {
            $copy->update(['status' => 'borrowed']);
        }

        Loan::updateOrCreate(
            ['loan_code' => 'PINJ-2026-081'],
            [
                'user_id' => $mahasiswaUser->id,
                'book_id' => $startupBook->id,
                'book_copy_id' => $copy ? $copy->id : null,
                'borrow_date' => Carbon::now()->subDays(7)->toDateString(),
                'due_date' => Carbon::now()->addDays(7)->toDateString(),
                'status' => 'borrowed',
                'extend_count' => 0,
                'max_extend' => 2,
            ]
        );

        $aiBook = Book::where('slug', 'arsitektur-kecerdasan-buatan-modern-deep-learning-llm')->first();
        Loan::updateOrCreate(
            ['loan_code' => 'PINJ-2026-077'],
            [
                'user_id' => $mahasiswaUser->id,
                'book_id' => $aiBook->id,
                'book_copy_id' => null,
                'borrow_date' => Carbon::now()->subDays(3)->toDateString(),
                'due_date' => Carbon::now()->addDays(11)->toDateString(),
                'status' => 'borrowed',
                'extend_count' => 1,
                'max_extend' => 2,
            ]
        );
    }
}
