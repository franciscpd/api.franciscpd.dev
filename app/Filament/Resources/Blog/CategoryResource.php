<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\CategoryResource\Pages;
use App\Models\Blog\Category;

use Filament\Tables;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use FilamentTiptapEditor\TiptapEditor;

use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'blog/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('blog.name'))
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Category::class, 'slug', ignoreRecord: true),

                TiptapEditor::make('description')
                    ->label(__('blog.description'))
                    ->profile('simple')
                    ->columnSpan('full'),

                Toggle::make('is_visible')
                    ->label(__('blog.visible'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('blog.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('is_visible')
                    ->label(__('blog.visibility')),
                TextColumn::make('updated_at')
                    ->label(__('blog.last_updated'))
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('blog.category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('blog.categories');
    }

    protected static function getNavigationGroup(): ?string
    {
        return 'Blog';
    }
}
