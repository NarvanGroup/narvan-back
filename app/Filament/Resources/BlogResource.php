<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Api\Transformers\BlogTransformer;
use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use App\Traits\ReplicationTrait;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Str;

class BlogResource extends Resource
{
    use ReplicationTrait;

    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

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
                RichEditor::make('content'),
                Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('category_id')
                    ->required()
                    ->relationship('category', 'name_fa')
                    ->searchable()
                    ->preload(),
                Select::make('sub_category_id')
                    ->required()
                    ->relationship(
                        name: 'subCategory',
                        titleAttribute: 'name_fa',
                        modifyQueryUsing: fn (Builder $query, $get) => $query->where('category_id',$get('category_id')),
                    )
                    ->searchable()
                    ->preload(),
                FileUpload::make('images')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->image()
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(
                        static fn(TemporaryUploadedFile $file): string => (string) str(Str::uuid())
                            ->prepend('blog-')
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
                TextColumn::make('name_en')
                    ->searchable(),
                TextColumn::make('name_fa')
                    ->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('content')
                    ->html(),
                TextColumn::make('description'),
                ImageColumn::make('images')
                    ->defaultImageUrl(url('/images/no image.png'))
                    ->circular()
                    ->stacked()
                    ->ring(5)
                    ->overlap(2)
                    ->limit()
                    ->limitedRemainingText()
                    ->size(50),
                TextColumn::make('user.name'),
                TextColumn::make('category.name_fa'),
                TextColumn::make('subCategory.name_fa'),
                TextColumn::make('keywords'),
                TextColumn::make('tags'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ReplicateAction::make()
                    ->beforeReplicaSaved(static function (Model $replica): void {
                        static::replicate($replica);
                    }),
                DeleteAction::make(),
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
