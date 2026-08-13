<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePcAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return (bool) $user?->hasPermission('menu.pc.view');
    }

    public function rules(): array
    {
        return [
            // ── Vendeur ───────────────────────────────────────
            'seller.pseudo'              => ['required', 'string', 'max:100'],
            'seller.email'               => ['required', 'email', 'max:255'],
            'seller.phone'               => ['required', 'string', 'max:20'],
            'seller.city'                => ['required', 'string', 'max:100'],

            // ── Compte bancaire ───────────────────────────────
            'bank.iban'                  => ['required', 'string', 'max:34'],
            'bank.bic'                   => ['required', 'string', 'max:11'],
            'bank.bank_name'             => ['nullable', 'string', 'max:100'],
            'bank.account_holder_name'   => ['required', 'string', 'max:150'],

            // ── Annonce ───────────────────────────────────────
            'ad.title'                   => ['required', 'string', 'max:255'],
            'ad.description'             => ['nullable', 'string', 'max:5000'],
            'ad.price'                   => ['required', 'numeric', 'min:0'],
            'ad.city'                    => ['required', 'string', 'max:100'],
            'ad.postal_code'             => ['nullable', 'string', 'max:10'],

            // ── Ordinateur ────────────────────────────────────
            'computer.brand'             => ['required', 'string', 'max:100'],
            'computer.model'             => ['required', 'string', 'max:100'],
            'computer.cpu'               => ['required', 'string', 'max:100'],
            'computer.ram_gb'            => ['required', 'integer', 'min:1', 'max:512'],
            'computer.storage_type'      => ['required', 'string', 'in:ssd,hdd'],
            'computer.storage_gb'        => ['required', 'integer', 'min:1', 'max:20000'],
            'computer.gpu'               => ['nullable', 'string', 'max:100'],
            'computer.screen_size'       => ['nullable', 'numeric', 'min:0', 'max:99'],
            'computer.os'                => ['nullable', 'string', 'max:100'],
            'computer.condition'         => ['nullable', 'string', 'max:100'],
            'computer.color'             => ['nullable', 'string', 'max:50'],

            // ── Équipements ───────────────────────────────────
            'features'                   => ['nullable', 'array'],
            'features.*'                 => ['string', 'max:100'],

            // ── Photos ────────────────────────────────────────
            'photos'                     => ['required', 'array', 'min:1', 'max:12'],
            'photos.*'                   => ['file', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'seller.pseudo.required'         => 'Le pseudo du vendeur est obligatoire.',
            'seller.email.required'          => "L'email du vendeur est obligatoire.",
            'seller.email.email'             => "L'email du vendeur est invalide.",
            'seller.phone.required'          => 'Le téléphone est obligatoire.',
            'seller.city.required'           => 'La ville du vendeur est obligatoire.',
            'bank.iban.required'             => "L'IBAN est obligatoire.",
            'bank.bic.required'              => 'Le BIC est obligatoire.',
            'bank.account_holder_name.required' => 'Le titulaire du compte est obligatoire.',
            'ad.title.required'              => "Le titre de l'annonce est obligatoire.",
            'ad.price.required'              => 'Le prix est obligatoire.',
            'ad.price.min'                   => 'Le prix doit être positif.',
            'ad.city.required'               => "La ville de l'annonce est obligatoire.",
            'computer.brand.required'        => 'La marque est obligatoire.',
            'computer.model.required'        => 'Le modèle est obligatoire.',
            'computer.cpu.required'          => 'Le processeur est obligatoire.',
            'computer.ram_gb.required'       => 'La mémoire vive est obligatoire.',
            'computer.storage_type.required' => 'Le type de stockage est obligatoire.',
            'computer.storage_gb.required'   => 'La capacité de stockage est obligatoire.',
            'photos.required'                => 'Au moins une photo est obligatoire.',
            'photos.min'                     => 'Au moins une photo est obligatoire.',
            'photos.max'                     => 'Maximum 12 photos autorisées.',
            'photos.*.mimes'                 => 'Formats acceptés : JPG, PNG, WEBP.',
            'photos.*.max'                   => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ];
    }
}
