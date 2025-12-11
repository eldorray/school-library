<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'web',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Librarian User
        $librarian = User::create([
            'name' => 'Pustakawan',
            'email' => 'librarian@example.com',
            'password' => Hash::make('password'),
            'role' => 'librarian',
        ]);

        // Create Teacher User
        $teacher = User::create([
            'name' => 'Guru Demo',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Create Student User
        $student = User::create([
            'name' => 'Siswa Demo',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // Create Member profiles
        Member::create([
            'user_id' => $teacher->id,
            'member_id' => 'NIP001',
            'type' => 'teacher',
            'department' => 'Matematika',
            'phone' => '081234567890',
            'join_date' => Carbon::now()->subYear(),
            'is_active' => true,
        ]);

        $studentMember = Member::create([
            'user_id' => $student->id,
            'member_id' => 'NIS001',
            'type' => 'student',
            'class' => 'XII IPA 1',
            'phone' => '081234567891',
            'join_date' => Carbon::now()->subMonths(6),
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Fiksi', 'slug' => 'fiksi', 'description' => 'Novel, cerpen, dan karya fiksi lainnya'],
            ['name' => 'Non-Fiksi', 'slug' => 'non-fiksi', 'description' => 'Buku pengetahuan umum dan referensi'],
            ['name' => 'Sains', 'slug' => 'sains', 'description' => 'Buku-buku ilmu pengetahuan alam'],
            ['name' => 'Matematika', 'slug' => 'matematika', 'description' => 'Buku pelajaran dan referensi matematika'],
            ['name' => 'Sejarah', 'slug' => 'sejarah', 'description' => 'Buku sejarah Indonesia dan dunia'],
            ['name' => 'Bahasa', 'slug' => 'bahasa', 'description' => 'Buku bahasa Indonesia dan asing'],
            ['name' => 'Agama', 'slug' => 'agama', 'description' => 'Buku pendidikan agama'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'description' => 'Buku teknologi informasi dan komputer'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Sample Books
        $books = [
            ['isbn' => '978-602-0000-01', 'title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'publish_year' => 2005, 'category_id' => 1, 'description' => 'Novel tentang perjuangan anak-anak Belitung', 'total_copies' => 5, 'available_copies' => 4],
            ['isbn' => '978-602-0000-02', 'title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'publish_year' => 1980, 'category_id' => 1, 'description' => 'Novel sejarah Indonesia', 'total_copies' => 3, 'available_copies' => 3],
            ['isbn' => '978-602-0000-03', 'title' => 'Fisika SMA Kelas XII', 'author' => 'Tim Penulis', 'publisher' => 'Erlangga', 'publish_year' => 2023, 'category_id' => 3, 'description' => 'Buku pelajaran fisika', 'total_copies' => 10, 'available_copies' => 8],
            ['isbn' => '978-602-0000-04', 'title' => 'Matematika Dasar', 'author' => 'Prof. Ahmad', 'publisher' => 'Gramedia', 'publish_year' => 2022, 'category_id' => 4, 'description' => 'Buku matematika untuk SMA', 'total_copies' => 8, 'available_copies' => 7],
            ['isbn' => '978-602-0000-05', 'title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs', 'publisher' => 'Serambi', 'publish_year' => 2008, 'category_id' => 5, 'description' => 'Sejarah Indonesia sejak abad ke-13', 'total_copies' => 4, 'available_copies' => 4],
            ['isbn' => '978-602-0000-06', 'title' => 'Tata Bahasa Baku Indonesia', 'author' => 'Hasan Alwi', 'publisher' => 'Balai Pustaka', 'publish_year' => 2010, 'category_id' => 6, 'description' => 'Panduan EYD dan tata bahasa', 'total_copies' => 6, 'available_copies' => 5],
            ['isbn' => '978-602-0000-07', 'title' => 'Pemrograman Web Modern', 'author' => 'John Doe', 'publisher' => 'Informatika', 'publish_year' => 2024, 'category_id' => 8, 'description' => 'Panduan pemrograman web dengan teknologi terbaru', 'total_copies' => 5, 'available_copies' => 5],
            ['isbn' => '978-602-0000-08', 'title' => 'Ensiklopedia Sains', 'author' => 'Tim Penulis', 'publisher' => 'Gramedia', 'publish_year' => 2021, 'category_id' => 2, 'description' => 'Referensi lengkap ilmu pengetahuan', 'total_copies' => 2, 'available_copies' => 2],
            ['isbn' => '978-602-0000-09', 'title' => 'Ayat-Ayat Cinta', 'author' => 'Habiburrahman El Shirazy', 'publisher' => 'Republika', 'publish_year' => 2004, 'category_id' => 1, 'description' => 'Novel islami bestseller', 'total_copies' => 4, 'available_copies' => 3],
            ['isbn' => '978-602-0000-10', 'title' => 'Kimia SMA Kelas XI', 'author' => 'Tim Penulis', 'publisher' => 'Yudhistira', 'publish_year' => 2023, 'category_id' => 3, 'description' => 'Buku pelajaran kimia', 'total_copies' => 10, 'available_copies' => 10],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Create Sample Borrowing
        Borrowing::create([
            'member_id' => $studentMember->id,
            'book_id' => 1,
            'issued_by' => $librarian->id,
            'borrow_date' => Carbon::now()->subDays(7),
            'due_date' => Carbon::now()->addDays(7),
            'status' => 'borrowed',
        ]);
    }
}
