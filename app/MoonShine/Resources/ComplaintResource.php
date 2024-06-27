<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Complaint;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Textarea;
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
                BelongsTo::make('User', 'user', fn($i) => "$i->name", resource: new UserResource),
                BelongsTo::make('Paste ID', 'paste', resource: new PasteResource),
                Text::make('Reason')
                    ->readonly()
                    ->hideOnIndex(),
                BelongsTo::make('Paste title', 'paste', fn($i) => "$i->title", resource: new PasteResource)
                    ->hideOnIndex(),
                Textarea::make('Content', 'paste.paste_content')
                    ->readonly()
                    ->hideOnIndex(),
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

