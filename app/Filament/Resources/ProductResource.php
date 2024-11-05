<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Storage;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_product')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('stock')
                ->required(),
                Forms\Components\TextInput::make('price')
                ->required(),
                Select::make('status')
                ->options([
                    'unpublished' => 'Unpublished',
                    'published' => 'Published',
                    'stockout' => 'Stockout',
                ])
                ->required(),
                Forms\Components\Textarea::make('description')
                ->required()
                ->rows(10)
                ->cols(20),
                FileUpload::make('image')
                ->image()
                ->maxSize(10400)
                ->disk(name: 'public')
                ->directory('product') 
                ->visibility('private')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_product')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                ->sortable(),
                Tables\Columns\TextColumn::make('price')
                ->money('IDR')
                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'published' => 'success',
                    'unpublished' => 'danger',
                    'stockout' => 'warning',
                })
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        // Loop melalui semua record yang dipilih untuk dihapus
                        foreach ($records as $record) {
                            // Hapus file terkait sebelum record dihapus
                            if ($record->image) {
                                Storage::disk('public')->delete($record->image);  // Hapus file dari storage
                            }
                        }
                    }),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
