<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Api\Transformers\CategoryTransformer;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Traits\ReplicationTrait;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends Resource
{

    use ReplicationTrait;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form->schema([
                TextInput::make('name_en')->unique(ignoreRecord: true)->required()->maxLength(255),
                TextInput::make('name_fa')->unique(ignoreRecord: true)->required()->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name_en')->searchable(),
            TextColumn::make('name_fa')->searchable(),
            TextColumn::make('slug')
            ])->filters([//
            ])->actions([
                Tables\Actions\EditAction::make(),
            ReplicateAction::make()
                ->beforeReplicaSaved(static function (Model $replica): void {
                    static::replicate($replica);
                }),
            DeleteAction::make(),
            ])->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubCategoryRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\BlogsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'), 'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return CategoryTransformer::class;
    }
}
