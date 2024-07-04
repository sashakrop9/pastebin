<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\AccessType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paste;

use Illuminate\Http\Request;
use MoonShine\Fields\Enum;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Paste>
 */
class PasteResource extends ModelResource
{
    protected string $model = Paste::class;

    protected string $title = 'Pastes';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Title', 'title')->readonly(),
                BelongsTo::make('Owner', 'user', fn($i) => "$i->name", resource: new UserResource),
                Enum::make('Access')
                    ->attach(AccessType::class)
                    ->readonly()
                    ->hideOnIndex(),
                Textarea::make('Content', 'paste_content')
                    ->readonly()
                    ->hideOnIndex(),
            ])
        ];
    }

    /**
     * @param Paste $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
