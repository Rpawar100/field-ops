<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordStageActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('demo:record');
    }

    public function rules(): array
    {
        return [
            'photo' => 'nullable|string', // Base64 or URL
            'observation' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $activityId = $this->route('id');
            $activity = \App\Models\DemoStageActivity::with('stageTemplate')
                ->find($activityId);

            if ($activity && $activity->stageTemplate) {
                // Check if photo is required
                if ($activity->stageTemplate->requires_photo && !$this->filled('photo')) {
                    $validator->errors()->add('photo', 'Photo is required for this stage.');
                }

                // Check if observation is required
                if ($activity->stageTemplate->requires_observation && !$this->filled('observation')) {
                    $validator->errors()->add('observation', 'Observation is required for this stage.');
                }
            }
        });
    }
}
