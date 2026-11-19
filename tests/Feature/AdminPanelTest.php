<?php

namespace Tests\Feature;

use App\Filament\Resources\QuestionCategories\Pages\CreateQuestionCategory;
use App\Filament\Resources\Questions\Pages\CreateQuestion;
use App\Filament\Resources\Questions\Pages\ListQuestions;
use App\Filament\Resources\Rooms\Pages\ListRooms;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Room;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The schema is owned by closer-api, so tests migrate from its migration directory.
     */
    protected function migrateFreshUsing(): array
    {
        return ['--path' => realpath(base_path('../closer-api/database/migrations')), '--realpath' => true];
    }

    private function admin(): User
    {
        return User::create(['name' => 'A', 'email' => 'a@example.test', 'password' => 'password123', 'is_admin' => true]);
    }

    public function test_non_admin_cannot_access_panel(): void
    {
        $user = User::create(['name' => 'U', 'email' => 'u@example.test', 'password' => 'password123']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_create_category_with_subcategories(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateQuestionCategory::class)
            ->fillForm([
                'name' => 'Travel',
                'slug' => 'travel',
                'subcategories' => [['name' => 'Roadtrips', 'slug' => 'roadtrips', 'active' => true]],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame('roadtrips', QuestionCategory::firstWhere('slug', 'travel')->subcategories->first()->slug);
    }

    public function test_admin_can_create_and_list_question(): void
    {
        $this->actingAs($this->admin());
        $category = QuestionCategory::create(['slug' => 'travel', 'name' => 'Travel']);

        Livewire::test(CreateQuestion::class)
            ->fillForm(['text' => 'Where next?', 'category_id' => $category->id, 'depth' => 'light', 'min_age' => 16, 'language' => 'en'])
            ->call('create')
            ->assertHasNoFormErrors();

        $question = Question::firstOrFail();
        $this->assertStringStartsWith('ADM-', $question->key);

        Livewire::test(ListQuestions::class)->assertCanSeeTableRecords([$question]);
    }

    public function test_admin_can_close_an_open_room(): void
    {
        $this->actingAs($this->admin());
        $room = Room::create([
            'ulid' => (string) Str::ulid(), 'code' => 'ABCD', 'active_code' => 'ABCD', 'comfort_excluded_tags' => [], 'expires_at' => now()->addHour(),
        ]);

        Livewire::test(ListRooms::class)
            ->assertCanSeeTableRecords([$room])
            ->callAction(TestAction::make('close')->table($room));

        $room->refresh();
        $this->assertFalse($room->isOpen());
        $this->assertNull($room->active_code);
    }
}
