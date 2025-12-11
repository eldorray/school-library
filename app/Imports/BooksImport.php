<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class BooksImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private array $categoryCache = [];
    private int $successCount = 0;

    public function model(array $row)
    {
        // Get or cache category by name
        $categoryName = trim($row['kategori'] ?? '');
        
        if (empty($categoryName)) {
            return null;
        }

        if (!isset($this->categoryCache[$categoryName])) {
            $category = Category::where('name', 'like', $categoryName)->first();
            $this->categoryCache[$categoryName] = $category?->id;
        }

        $categoryId = $this->categoryCache[$categoryName];
        
        if (!$categoryId) {
            return null;
        }

        $totalCopies = (int) ($row['jumlah_eksemplar'] ?? 1);

        $this->successCount++;

        return new Book([
            'isbn'             => $row['isbn'] ?? null,
            'title'            => $row['judul'],
            'author'           => $row['penulis'],
            'publisher'        => $row['penerbit'] ?? null,
            'publish_year'     => $row['tahun_terbit'] ?? null,
            'category_id'      => $categoryId,
            'description'      => $row['deskripsi'] ?? null,
            'total_copies'     => $totalCopies,
            'available_copies' => $totalCopies,
        ]);
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'deskripsi' => ['nullable', 'string'],
            'jumlah_eksemplar' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'judul.required' => 'Kolom judul wajib diisi',
            'penulis.required' => 'Kolom penulis wajib diisi',
            'kategori.required' => 'Kolom kategori wajib diisi',
        ];
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}
