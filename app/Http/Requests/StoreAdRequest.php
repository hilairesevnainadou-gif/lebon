<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // ── Vendeur ───────────────────────────────────────
            'seller.first_name'         => ['required', 'string', 'max:100'],
            'seller.last_name'          => ['required', 'string', 'max:100'],
            'seller.email'              => ['required', 'email', 'max:255'],
            'seller.phone'              => ['required', 'string', 'max:20'],
            'seller.city'               => ['required', 'string', 'max:100'],

            // ── Compte bancaire ───────────────────────────────
            'bank.iban'                 => ['required', 'string', 'max:34'],
            'bank.bic'                  => ['required', 'string', 'max:11'],
            'bank.bank_name'            => ['nullable', 'string', 'max:100'],
            'bank.account_holder_name'  => ['required', 'string', 'max:150'],

            // ── Annonce ───────────────────────────────────────
            'ad.title'                  => ['required', 'string', 'max:255'],
            'ad.description'            => ['nullable', 'string', 'max:5000'],
            'ad.price'                  => ['required', 'numeric', 'min:0'],
            'ad.city'                   => ['required', 'string', 'max:100'],
            'ad.postal_code'            => ['nullable', 'string', 'max:10'],
            'ad.likes_count'            => ['nullable', 'integer', 'min:0'],

            // ── Véhicule ──────────────────────────────────────
            'vehicle.brand'                   => ['required', 'string', 'max:100'],
            'vehicle.model'                   => ['required', 'string', 'max:100'],
            'vehicle.year'                    => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'vehicle.mileage'                 => ['required', 'integer', 'min:0', 'max:999999'],
            'vehicle.fuel_type'               => ['required', 'string', 'in:diesel,essence,hybride,electrique,gpl,autre'],
            'vehicle.gearbox'                 => ['required', 'string', 'in:manuelle,automatique'],
            'vehicle.doors'                   => ['nullable', 'integer', 'min:1', 'max:9'],
            'vehicle.seats'                   => ['nullable', 'integer', 'min:1', 'max:20'],
            'vehicle.body_type'               => ['nullable', 'string', 'max:100'],
            'vehicle.color'                   => ['nullable', 'string', 'max:50'],
            'vehicle.din_power'               => ['nullable', 'integer', 'min:0', 'max:9999'],
            'vehicle.fiscal_power'            => ['nullable', 'integer', 'min:0', 'max:999'],
            'vehicle.critair'                 => ['nullable', 'integer', 'min:0', 'max:5'],
            'vehicle.upholstery'              => ['nullable', 'string', 'max:100'],
            'vehicle.first_registration_date' => ['nullable', 'date'],
            'vehicle.condition'               => ['nullable', 'string', 'max:100'],

            // ── Équipements ───────────────────────────────────
            'features'                  => ['nullable', 'array'],
            'features.*'                => ['string', 'max:100'],

            // ── Photos ────────────────────────────────────────
            'photos'                    => ['required', 'array', 'min:1', 'max:12'],
            'photos.*'                  => ['file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'seller.first_name.required'        => 'Le prénom du vendeur est obligatoire.',
            'seller.last_name.required'         => 'Le nom du vendeur est obligatoire.',
            'seller.email.required'             => "L'email du vendeur est obligatoire.",
            'seller.email.email'                => "L'email du vendeur est invalide.",
            'seller.phone.required'             => 'Le téléphone est obligatoire.',
            'seller.city.required'              => 'La ville du vendeur est obligatoire.',
            'bank.iban.required'                => "L'IBAN est obligatoire.",
            'bank.bic.required'                 => 'Le BIC est obligatoire.',
            'bank.account_holder_name.required' => 'Le titulaire du compte est obligatoire.',
            'ad.title.required'                 => "Le titre de l'annonce est obligatoire.",
            'ad.price.required'                 => 'Le prix est obligatoire.',
            'ad.price.min'                      => 'Le prix doit être positif.',
            'ad.city.required'                  => "La ville de l'annonce est obligatoire.",
            'vehicle.brand.required'            => 'La marque est obligatoire.',
            'vehicle.model.required'            => 'Le modèle est obligatoire.',
            'vehicle.year.required'             => "L'année est obligatoire.",
            'vehicle.year.max'                  => "L'année ne peut pas être dans le futur.",
            'vehicle.mileage.required'          => 'Le kilométrage est obligatoire.',
            'vehicle.mileage.max'               => 'Le kilométrage ne peut pas dépasser 999 999 km.',
            'vehicle.fuel_type.required'        => 'Le carburant est obligatoire.',
            'vehicle.gearbox.required'          => 'La boîte de vitesse est obligatoire.',
            'photos.required'                   => 'Au moins une photo est obligatoire.',
            'photos.min'                        => 'Au moins une photo est obligatoire.',
            'photos.max'                        => 'Maximum 12 photos autorisées.',
            'photos.*.mimes'                    => 'Formats acceptés : JPG, PNG, WEBP.',
            'photos.*.max'                      => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ];
    }
}
