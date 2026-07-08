<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Paramètres du site';

    protected static ?string $title = 'Paramètres du site';

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->only(['footer_tagline', 'shipping_fee']));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('footer_tagline')
                    ->label('Texte du pied de page')
                    ->helperText('Affiché sous le logo dans le pied de page du site. Laissez vide pour utiliser le texte par défaut.')
                    ->rows(3)
                    ->maxLength(500),
                Forms\Components\TextInput::make('shipping_fee')
                    ->label('Frais de livraison')
                    ->helperText('Montant ajouté au sous-total lors de la commande (en DT). Laissez vide pour utiliser la valeur par défaut (' . number_format(\App\Models\Command::SHIPPING_FEE, 2) . ' DT).')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('DT'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Paramètres enregistrés')
            ->success()
            ->send();
    }
}
