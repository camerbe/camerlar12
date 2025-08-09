<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            'prenom'=>$this->prenom,
            'fullName'=>$this->nom." ".$this->prenom,
            'email'=>$this->email,
            'role'=>$this->role,
            'email_verified_at'=>$this->email_verified_at,
            'password_changed_at'=>$this->password_changed_at,
        ];
    }
}
