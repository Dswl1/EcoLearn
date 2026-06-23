<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin123@gmail.com')->first();

        $contents = [
            [
                'title' => 'Mengenal SDGs: Mengakhiri Kemiskinan',
                'title_en' => 'Introduction to SDGs: Ending Poverty',
                'sdg_category' => 'SDG 1',
                'difficulty' => 'basic',
                'description' => 'Pelajari tentang tujuan pertama SDGs yaitu mengakhiri kemiskinan dalam segala bentuk di mana pun.',
                'body' => '<p>Kemiskinan adalah masalah global yang kompleks dan multidimensi. SDG 1 bertujuan untuk mengakhiri kemiskinan ekstrem bagi semua orang di mana pun, yang saat ini diukur dengan pendapatan kurang dari $1,25 per hari.</p><p>Lebih dari 700 juta orang masih hidup dalam kemiskinan ekstrem. Mereka berjuang untuk memenuhi kebutuhan paling dasar seperti kesehatan, pendidikan, dan akses ke air bersih.</p><p>Target utama SDG 1 meliputi pengentasan kemiskinan ekstrem, mengurangi setengah proporsi penduduk yang hidup dalam kemiskinan, dan menerapkan sistem perlindungan sosial yang tepat.</p>',
                'tags' => 'sdg 1, kemiskinan, sosial',
            ],
            [
                'title' => 'Edukasi Berkualitas untuk Semua',
                'title_en' => 'Quality Education for All',
                'sdg_category' => 'SDG 4',
                'difficulty' => 'basic',
                'description' => 'Menjelajahi pentingnya pendidikan berkualitas yang inklusif dan merata untuk semua.',
                'body' => '<p>Pendidikan adalah kunci untuk membuka potensi penuh setiap individu. SDG 4 bertujuan memastikan pendidikan yang inklusif dan berkualitas secara merata serta mendukung kesempatan belajar seumur hidup bagi semua.</p><p>Meskipun telah ada kemajuan signifikan, masih ada jutaan anak yang tidak bersekolah. Anak perempuan, anak berkebutuhan khusus, dan mereka yang tinggal di daerah konflik sering kali tertinggal.</p><p>Target SDG 4 mencakup pendidikan dasar dan menengah gratis, akses ke pelatihan kejuruan, dan penghapusan kesenjangan gender dalam pendidikan.</p>',
                'tags' => 'sdg 4, pendidikan, sosial',
            ],
            [
                'title' => 'Aksi Iklim: Melindungi Bumi Kita',
                'title_en' => 'Climate Action: Protecting Our Earth',
                'sdg_category' => 'SDG 13',
                'difficulty' => 'core',
                'description' => 'Memahami perubahan iklim, dampaknya, dan langkah-langkah yang bisa kita ambil.',
                'body' => '<p>Perubahan iklim adalah salah satu tantangan terbesar di zaman kita. SDG 13 menyerukan aksi segera untuk memerangi perubahan iklim dan dampaknya.</p><p>Emisi gas rumah kaca terus meningkat, dan suhu global diproyeksikan naik lebih dari 1,5°C jika tren saat ini berlanjut. Dampaknya meliputi cuaca ekstrem, naiknya permukaan air laut, dan gangguan pada ekosistem.</p><p>Tindakan yang dapat dilakukan meliputi transisi ke energi terbarukan, mengurangi deforestasi, dan meningkatkan ketahanan iklim di masyarakat rentan.</p>',
                'tags' => 'sdg 13, iklim, lingkungan',
            ],
            [
                'title' => 'Inovasi Industri untuk Masa Depan',
                'title_en' => 'Industry Innovation for the Future',
                'sdg_category' => 'SDG 9',
                'difficulty' => 'expert',
                'description' => 'Membangun infrastruktur tangguh, industrialisasi inklusif, dan mendorong inovasi.',
                'body' => '<p>SDG 9 berfokus pada pembangunan infrastruktur yang tangguh, industrialisasi yang inklusif dan berkelanjutan, serta mendorong inovasi. Infrastruktur yang baik adalah fondasi ekonomi yang kuat.</p><p>Investasi dalam penelitian dan pengembangan (R&D) sangat penting untuk inovasi. Negara berkembang perlu dukungan untuk mengembangkan industri mereka dan berpartisipasi dalam rantai nilai global.</p><p>Akses ke internet dan teknologi informasi juga merupakan bagian penting dari target ini, terutama untuk menjembatani kesenjangan digital.</p>',
                'tags' => 'sdg 9, industri, inovasi, ekonomi',
            ],
            [
                'title' => 'Perdamaian, Keadilan, dan Kelembagaan',
                'title_en' => 'Peace, Justice, and Strong Institutions',
                'sdg_category' => 'SDG 16',
                'difficulty' => 'core',
                'description' => 'Mendorong masyarakat yang damai dan inklusif serta membangun institusi yang efektif.',
                'body' => '<p>SDG 16 bertujuan untuk mendorong masyarakat yang damai dan inklusif, memberikan akses keadilan bagi semua, dan membangun institusi yang efektif, akuntabel, dan inklusif di semua tingkat.</p><p>Konflik dan ketidakamanan masih menjadi ancaman besar bagi pembangunan berkelanjutan. Diperlukan institusi yang kuat dan transparan untuk memastikan keadilan dan melindungi hak asasi manusia.</p><p>Target utama termasuk mengurangi kekerasan dan angka kematian terkait konflik, menghentikan pelecehan dan perdagangan manusia, serta mengurangi korupsi dan suap.</p>',
                'tags' => 'sdg 16, perdamaian, keadilan, governance',
            ],
        ];

        foreach ($contents as $item) {
            Content::factory()->create([
                'user_id' => $admin->id,
                'title' => $item['title'],
                'slug' => Str::slug($item['title']).'-'.Str::random(6),
                'sdg_category' => $item['sdg_category'],
                'difficulty' => $item['difficulty'],
                'description' => $item['description'],
                'body' => $item['body'],
                'tags' => $item['tags'],
                'status' => 'published',
                'public_access' => true,
                'published_at' => now(),
            ]);
        }

        $this->command->info('5 dummy contents created successfully.');
    }
}
