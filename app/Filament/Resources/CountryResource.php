<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CountryResource extends Resource
{

    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(60)->minLength(3),
                TextInput::make('code')->required()->maxLength(2)->minLength(2),
                Toggle::make('is_visible'),
            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->limit(60)->sortable()->searchable(),
                TextColumn::make('code')->limit(2)->sortable()->searchable(),
                TextColumn::make('created_at')->sortable()->searchable(),
                TextColumn::make('updated_at')->sortable()->searchable(),
                BooleanColumn::make('is_visible')->toggleable()->searchable(),
            ])
            ->filters([
                TernaryFilter::make('is_visible')->label('Is visible'),
                Filter::make('created_at')
                      ->form([
                          DatePicker::make('created_from'),
                          DatePicker::make('created_until'),
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
                          DatePicker::make('updated_from'),
                          DatePicker::make('updated_until'),
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
                Tables\Actions\Action::make('toggle-visibility')->action(function(Country $record)
                {
                    $record->update(['is_visible' => !$record->is_visible]);
                })

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('toggle-visible')
                          ->label('Change visibility')
                          ->action(function (Collection $records) {
                              foreach ($records as $record) {
                                  $record->update(['is_visible' => !$record->is_visible]);
                              }
                          })
            ])
        ;
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
            'index'  => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit'   => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
