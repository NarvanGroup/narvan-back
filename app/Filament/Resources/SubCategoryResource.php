<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\BlogsRelationManager;
use App\Filament\Resources\CategoryResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\CategoryRelationManager;
use App\Filament\Resources\SubCategoryResource\Api\Transformers\SubCategoryTransformer;
use App\Filament\Resources\SubCategoryResource\Pages;
use App\Models\SubCategory;
use App\Traits\ReplicationTrait;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SubCategoryResource extends Resource
{

    use ReplicationTrait;

    protected static ?string $model = SubCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name_en')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_fa')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Select::make('category_id')
                    ->required()
                    ->relationship('category', 'name_fa')
                    ->searchable()
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')
                    ->searchable(),
                TextColumn::make('name_fa')
                    ->searchable(),
                TextColumn::make('slug')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ReplicateAction::make()
                    ->beforeReplicaSaved(static function (Model $replica): void {
                        static::replicate($replica);
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoryRelationManager::class,
            BlogsRelationManager::class,
            ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return SubCategoryTransformer::class;
    }
}
