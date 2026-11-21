<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionMode;
use App\Models\QuestionSubcategory;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Signature('questions:import {path : JSON catalog path} {--dry-run : Validate and count without writing}')] 
#[Description('Import a Closer question catalog into the shared question tables')]
class ImportQuestions extends \Illuminate\Console\Command
{
    public function handle(): int
    {
        $path = (string) $this->argument('path');

        if (! is_file($path)) {
            $this->error("Catalog not found: {$path}");

            return self::FAILURE;
        }

        $catalog = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $questions = Arr::wrap($catalog['questions'] ?? []);

        if ($questions === []) {
            $this->error('The catalog does not contain any questions.');

            return self::FAILURE;
        }

        $this->info(sprintf('Validated %d questions, %d modes, and %d categories.', count($questions), count($catalog['modes'] ?? []), count($catalog['categories'] ?? [])));

        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        DB::transaction(function () use ($catalog, $questions): void {
            $modes = [];
            foreach ($catalog['modes'] ?? [] as $mode) {
                $record = QuestionMode::updateOrCreate(
                    ['slug' => $mode['slug']],
                    [
                        'name' => $mode['name'],
                        'description' => $mode['description'] ?? null,
                        'suggested_session_size' => $mode['suggested_session_size'] ?? 10,
                        'progression' => $mode['progression'] ?? 'shuffle',
                        'allow_skip' => $mode['allow_skip'] ?? true,
                        'active' => $mode['active'] ?? true,
                    ],
                );
                $modes[$mode['slug']] = $record->id;
            }

            $categories = [];
            $subcategories = [];
            foreach ($catalog['categories'] ?? [] as $categoryIndex => $category) {
                $record = QuestionCategory::updateOrCreate(
                    ['slug' => $category['slug']],
                    [
                        'name' => $category['name'] ?? Str::headline($category['slug']),
                        'description' => $category['description'] ?? null,
                        'sort_order' => $category['sort_order'] ?? $categoryIndex,
                        'active' => $category['active'] ?? true,
                    ],
                );
                $categories[$category['slug']] = $record->id;

                foreach ($category['subcategories'] ?? [] as $subcategoryIndex => $subcategory) {
                    $subrecord = QuestionSubcategory::updateOrCreate(
                        ['category_id' => $record->id, 'slug' => $subcategory['slug']],
                        [
                            'name' => $subcategory['name'] ?? Str::headline($subcategory['slug']),
                            'sort_order' => $subcategory['sort_order'] ?? $subcategoryIndex,
                            'active' => $subcategory['active'] ?? true,
                        ],
                    );
                    $subcategories[$category['slug'].':'.$subcategory['slug']] = $subrecord->id;
                }
            }

            foreach (array_chunk($questions, 250) as $chunk) {
                foreach ($chunk as $question) {
                    $categorySlug = (string) ($question['category'] ?? '');
                    $subcategorySlug = (string) ($question['subcategory'] ?? '');

                    Question::updateOrCreate(
                        ['key' => $question['id']],
                        [
                            'category_id' => $categories[$categorySlug],
                            'subcategory_id' => $subcategories[$categorySlug.':'.$subcategorySlug] ?? null,
                            'mode_id' => $modes[$question['mode']] ?? null,
                            'text' => $question['question'],
                            'depth' => $question['depth'] ?? 'light',
                            'min_age' => $question['min_age'] ?? 16,
                            'sensitive' => $question['sensitive'] ?? false,
                            'language' => $question['language'] ?? 'en',
                            'active' => $question['active'] ?? true,
                            'tags' => $question['tags'] ?? [],
                        ],
                    );
                }
            }
        });

        $this->info('Question catalog imported successfully.');

        return self::SUCCESS;
    }
}
