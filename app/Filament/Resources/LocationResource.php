<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(256)->minLength(2),
                TextInput::make('code')->required()->maxLength(20)->minLength(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->limit(60)->sortable()->searchable(),
                TextColumn::make('code')->limit(2)->sortable()->searchable(),
                TextColumn::make('created_at')->sortable()->searchable(),
                TextColumn::make('updated_at')->sortable()->searchable()
            ])
            ->filters([
                Filter::make('created_at')
                      ->form([
                          Forms\Components\DatePicker::make('created_from'),
                          Forms\Components\DatePicker::make('created_until'),
                      ])
                      ->query(function (Builder $query, array $data): Builder {
                          return $query
                              ->when(
                                  $data['created_from'],
                                  fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                              )
                              ->when(
                                  $data['created_until'],
                                  fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                              );
                      }),
                Filter::make('updated_at')
                      ->form([
                          Forms\Components\DatePicker::make('updated_from'),
                          Forms\Components\DatePicker::make('updated_until'),
                      ])
                      ->query(function (Builder $query, array $data): Builder {
                          return $query
                              ->when(
                                  $data['updated_from'],
                                  fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '>=', $date),
                              )
                              ->when(
                                  $data['updated_until'],
                                  fn (Builder $query, $date): Builder => $query->whereDate('updated_at', '<=', $date),
                              );
                      })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }    
}
