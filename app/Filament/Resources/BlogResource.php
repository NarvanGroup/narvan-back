<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Api\Transformers\BlogTransformer;
use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                RichEditor::make('content'),
                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('category_id')
                    ->required()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('sub_category_id')
                    ->required()
                    ->relationship('subCategory', 'name')
                    ->searchable()
                    ->preload(),
                FileUpload::make('image')
                    ->nullable()
                    ->downloadable()
                    ->image()
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(
                        static fn(TemporaryUploadedFile $file): string => (string) str(Str::uuid())
                            ->prepend('product-')
                            ->append(sprintf(".%s", $file->extension())),
                    ),
                TextInput::make('description')->nullable(),
                KeyValue::make('keywords')->nullable(),
                TagsInput::make('tags')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('content')
                    ->html(),
                TextColumn::make('description'),
                ImageColumn::make('images')
                    ->defaultImageUrl(url('/images/no image.png'))
                    ->square()
                    ->stacked()
                    ->ring(8)
                    ->size(100),
                TextColumn::make('user.name'),
                TextColumn::make('category.name'),
                TextColumn::make('subCategory.name'),
                TextColumn::make('keywords'),
                TextColumn::make('tags'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return BlogTransformer::class;
    }
}
