<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $size = $request->query('size') ?? 5;
        $page = $request->query('page') ?? 1;
        $total_records = Project::count();
        return [
            'data' => $this->collection,
            'pagination' => [
                'size' => (int) $size,
                'total_pages' => ceil($total_records / $size),
                'previous_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < ceil($total_records / $size) ? $page + 1 : null,
            ],
        ];
    }
}
