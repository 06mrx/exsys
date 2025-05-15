<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Data 15 soal contoh tanpa kategori dan instansi
        $questions = [
            [
                'content' => 'Apa ibu kota Indonesia?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Jakarta', 'is_correct' => true],
                    ['content' => 'Surabaya', 'is_correct' => false],
                    ['content' => 'Bandung', 'is_correct' => false],
                    ['content' => 'Medan', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Berapakah hasil dari 2 + 3?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => '4', 'is_correct' => false],
                    ['content' => '5', 'is_correct' => true],
                    ['content' => '6', 'is_correct' => false],
                    ['content' => '7', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa arti kata "Hello" dalam bahasa Inggris?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Goodbye', 'is_correct' => false],
                    ['content' => 'Hello', 'is_correct' => true],
                    ['content' => 'Thank you', 'is_correct' => false],
                    ['content' => 'Please', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa hewan ini?',
                'category' => null,
                'institution_id' => null,
                'image_path' => 'uploads/questions/AlO7nXlks5sUUBR0HG4J3AZY5Ruag6No9cxdHrmp.jpg', // Ganti dengan file gambar yang valid
                'options' => [
                    ['content' => 'Gajah', 'is_correct' => true],
                    ['content' => 'Harimau', 'is_correct' => false],
                    ['content' => 'Singa', 'is_correct' => false],
                    ['content' => 'Zebra', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Siapa presiden pertama Indonesia?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Soekarno', 'is_correct' => true],
                    ['content' => 'Soeharto', 'is_correct' => false],
                    ['content' => 'Habibie', 'is_correct' => false],
                    ['content' => 'Megawati', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Berapakah 5 x 4?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => '15', 'is_correct' => false],
                    ['content' => '20', 'is_correct' => true],
                    ['content' => '25', 'is_correct' => false],
                    ['content' => '30', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa arti kata "Book" dalam bahasa Inggris?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Pen', 'is_correct' => false],
                    ['content' => 'Book', 'is_correct' => true],
                    ['content' => 'Table', 'is_correct' => false],
                    ['content' => 'Chair', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Berapakah 10 dibagi 2?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => '3', 'is_correct' => false],
                    ['content' => '5', 'is_correct' => true],
                    ['content' => '4', 'is_correct' => false],
                    ['content' => '6', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa nama gunung tertinggi di Indonesia?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Gunung Rinjani', 'is_correct' => false],
                    ['content' => 'Gunung Kerinci', 'is_correct' => false],
                    ['content' => 'Gunung Jaya Wijaya', 'is_correct' => false],
                    ['content' => 'Gunung Puncak Jaya', 'is_correct' => true],
                ],
            ],
            [
                'content' => 'Apa ibu kota Jepang?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Tokyo', 'is_correct' => true],
                    ['content' => 'Osaka', 'is_correct' => false],
                    ['content' => 'Kyoto', 'is_correct' => false],
                    ['content' => 'Hiroshima', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Berapakah 7 + 8?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => '14', 'is_correct' => false],
                    ['content' => '15', 'is_correct' => true],
                    ['content' => '16', 'is_correct' => false],
                    ['content' => '17', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa hewan ini?',
                'category' => null,
                'institution_id' => null,
                'image_path' => 'uploads/questions/AlO7nXlks5sUUBR0HG4J3AZY5Ruag6No9cxdHrmp.jpg', // Ganti dengan file gambar yang valid
                'options' => [
                    ['content' => 'Harimau', 'is_correct' => true],
                    ['content' => 'Singa', 'is_correct' => false],
                    ['content' => 'Cheetah', 'is_correct' => false],
                    ['content' => 'Leopard', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa nama laut di sebelah timur Indonesia?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => 'Laut Jawa', 'is_correct' => false],
                    ['content' => 'Laut Banda', 'is_correct' => false],
                    ['content' => 'Laut Arafuru', 'is_correct' => true],
                    ['content' => 'Laut Sulawesi', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Apa hewan ini?',
                'category' => null,
                'institution_id' => null,
                'image_path' => 'uploads/questions/AlO7nXlks5sUUBR0HG4J3AZY5Ruag6No9cxdHrmp.jpg', // Ganti dengan file gambar yang valid
                'options' => [
                    ['content' => 'Elang', 'is_correct' => true],
                    ['content' => 'Burung Hantu', 'is_correct' => false],
                    ['content' => 'Merpati', 'is_correct' => false],
                    ['content' => 'Kakak Tua', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Berapakah 100 - 25?',
                'category' => null,
                'institution_id' => null,
                'image_path' => null,
                'options' => [
                    ['content' => '65', 'is_correct' => false],
                    ['content' => '75', 'is_correct' => true],
                    ['content' => '85', 'is_correct' => false],
                    ['content' => '95', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $data) {
            // Simpan soal
            $question = Question::create([
                'content' => $data['content'],
                'category' => $data['category'],
                'institution_id' => $data['institution_id'],
                'image_path' => $data['image_path'],
            ]);

            // Simpan opsi jawaban
            foreach ($data['options'] as $index => $option) {
                Option::create([
                    'question_id' => $question->id,
                    'content' => $option['content'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }
    }
}