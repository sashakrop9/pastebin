<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Complaint;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Fields\Text;
use MoonShine\Components\MoonShineComponent;


/**
 * @extends ModelResource<Complaint>
 */
class ComplaintResource extends ModelResource
{
    protected string $model = Complaint::class;

    protected string $title = 'Complaints';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {

        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make('User', 'user', resource: new UserResource)->disabled(),
                BelongsTo::make('Paste', 'paste', resource: new PasteResource), // Добавляем связь с пастой
                Text::make('Reason')->readonly(),
                Text::make('Content', 'paste.paste_content')->readonly()
            ]),
        ];
    }

    /**
     * @param Complaint $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}

/**
 * 'resources' => [
 * [
 * 'label' => 'Жалобы',
 * 'model' => \App\Models\Complaint::class,
 * 'labelField' => 'id', // Поле модели, которое будет отображаться в списках
 * 'fields' => [
 * [
 * 'name' => 'user_id',
 * 'label' => 'Пользователь',
 * 'type' => 'belongsTo',
 * 'belongsToResource' => \App\Models\User::class,
 * ],
 * [
 * 'name' => 'paste_id',
 * 'label' => 'Паста',
 * 'type' => 'belongsTo',
 * 'belongsToResource' => \App\Models\Paste::class,
 * ],
 * [
 * 'name' => 'reason',
 * 'label' => 'Причина',
 * 'type' => 'text',
 * ],
 * [
 * 'name' => 'status',
 * 'label' => 'Статус',
 * 'type' => 'select',
 * 'options' => [
 * 'pending' => 'Ожидание',
 * 'approved' => 'Утверждено',
 * 'rejected' => 'Отклонено',
 * ],
 * ],
 * [
 * 'name' => 'created_at',
 * 'label' => 'Дата создания',
 * 'type' => 'dateTime',
 * ],
 * ],
 * ],
 */
