<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // ── Annonce ───────────────────────────────────────
            'ad.title'                  => ['required', 'string', 'max:255'],
            'ad.description'            => ['nullable', 'string', 'max:5000'],
            'ad.price'                  => ['required', 'numeric', 'min:0'],
            'ad.city'                   => ['required', 'string', 'max:100'],
            'ad.postal_code'            => ['nullable', 'string', 'max:10'],
            'ad.likes_count'            => ['nullable', 'integer', 'min:0'],
            'ad.status'                 => ['required', 'in:active,paused,sold'],

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

            // ── Nouvelles photos ──────────────────────────────
            'photos'                    => ['nullable', 'array', 'max:12'],
            'photos.*'                  => ['file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'ad.title.required'           => "Le titre est obligatoire.",
            'ad.price.required'           => 'Le prix est obligatoire.',
            'ad.city.required'            => "La ville est obligatoire.",
            'vehicle.brand.required'      => 'La marque est obligatoire.',
            'vehicle.model.required'      => 'Le modèle est obligatoire.',
            'vehicle.year.required'       => "L'année est obligatoire.",
            'vehicle.mileage.required'    => 'Le kilométrage est obligatoire.',
            'vehicle.fuel_type.required'  => 'Le carburant est obligatoire.',
            'vehicle.gearbox.required'    => 'La boîte de vitesse est obligatoire.',
            'photos.*.mimes'              => 'Formats acceptés : JPG, PNG, WEBP.',
            'photos.*.max'                => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ];
    }
}
