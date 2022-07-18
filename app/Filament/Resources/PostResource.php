<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostsRelationManagerResource\RelationManagers\CountryRelationManager;
use App\Filament\Resources\PostsRelationManagerResource\RelationManagers\TagRelationManager;
use App\Models\Country;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Grid::make(['default' => 1])
                                     ->schema([
                                         Tabs::make('Post')
                                             ->tabs([
                                                 Tabs\Tab::make('Texts')
                                                         ->schema([
                                                             TextInput::make('title')
                                                                      ->required()
                                                                      ->maxLength(60)
                                                                      ->minLength(3)
                                                                      ->reactive()
                                                                      ->afterStateUpdated(fn(
                                                                          $state,
                                                                          callable $set
                                                                      ) => $set('slug',
                                                                          Str::slug($state)))
                                                             ,
                                                             TextInput::make('slug')
                                                                      ->required()
                                                                      ->maxLength(80)
                                                                      ->minLength(3),
                                                             Forms\Components\RichEditor::make('content')
                                                                                        ->required()
                                                                                        ->columns(10)
                                                                                        ->hint('Translatable')
                                                                                        ->hintIcon('heroicon-s-translate')
                                                                                        ->maxLength(32000),
                                                         ]),
                                                 Tabs\Tab::make('Images')
                                                         ->schema([
                                                             Forms\Components\Grid::make([
                                                                 'default' => 2,
                                                                 'xs'      => 1,
                                                                 's'       => 1,
                                                             ])
                                                                                  ->schema([
                                                                                      Forms\Components\FileUpload::make('main_image_url')
                                                                                                                 ->required()
                                                                                                                 ->image()
                                                                                                                 ->label('Main Image'),
                                                                                      Forms\Components\FileUpload::make('images')
                                                                                                                 ->multiple()
                                                                                                                 ->image()
                                                                                                                 ->enableReordering()
                                                                                                                 ->label('Other Images'),
                                                                                  ]),
                                                         ]),
                                                 Tabs\Tab::make('Selects')
                                                         ->schema([
                                                             Forms\Components\Select::make('country_id')
                                                                                    ->options(Country::all()
                                                                                                     ->pluck('name',
                                                                                                         'id'))
                                                                                    ->label('Country'),
                                                         ]),
                                             ]),
                                     ]),

            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('title')->limit(60)->sortable()->searchable(),
                TextColumn::make('country.name')->limit(15)->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('main_image_url')->label('Main Image'),
                Tables\Columns\TagsColumn::make('tags.text')->label('Tags'),
                TextColumn::make('created_at')->sortable()->searchable(),
                TextColumn::make('updated_at')->sortable()->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
        ;
    }


    public static function getRelations(): array
    {
        return [
            TagRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
