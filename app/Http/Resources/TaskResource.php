<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource === null) {
            return [];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'project' => $this->project ? [
                'id' => $this->project->id,
                'name' => $this->project->name,
                'description' => $this->project->description,
                'created_at' => $this->project->created_at,
                'updated_at' => $this->project->updated_at,
            ] : null,
        ];
    }
}
