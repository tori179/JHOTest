<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use App\Models\Stage;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    // Lấy danh sách pipeline
    public function index()
    {
        $pipelines = Pipeline::with('stages.opportunities')->get();
        return response()->json($pipelines);
    }

    // Tạo mới pipeline
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stages' => 'array',
            'stages.*.name' => 'required|string|max:255',
            'stages.*.position' => 'integer'
        ]);

        $pipeline = Pipeline::create(['name' => $request->name]);

        if ($request->has('stages')) {
            foreach ($request->stages as $stageData) {
                $pipeline->stages()->create($stageData);
            }
        }

        return response()->json($pipeline->load('stages'), 201);
    }

    // Xem chi tiết pipeline
    public function show($id)
    {
        $pipeline = Pipeline::with('stages.opportunities')->findOrFail($id);
        return response()->json($pipeline);
    }

    // Cập nhật pipeline
    public function update(Request $request, $id)
    {
        $pipeline = Pipeline::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'stages' => 'array',
            'stages.*.id' => 'exists:stages,id',
            'stages.*.name' => 'string|max:255',
            'stages.*.position' => 'integer'
        ]);

        $pipeline->update(['name' => $request->name]);

        if ($request->has('stages')) {
            foreach ($request->stages as $stageData) {
                if (isset($stageData['id'])) {
                    $stage = Stage::find($stageData['id']);
                    if ($stage) {
                        $stage->update($stageData);
                    }
                } else {
                    $pipeline->stages()->create($stageData);
                }
            }
        }

        return response()->json($pipeline->load('stages'));
    }

    // Xóa pipeline
    public function destroy($id)
    {
        $pipeline = Pipeline::findOrFail($id);
        $pipeline->delete();
        return response()->json(['message' => 'Đã xóa pipeline']);
    }

    // Di chuyển opportunity giữa các stage
    public function moveOpportunity(Request $request, $opportunityId)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id'
        ]);

        $opportunity = Opportunity::findOrFail($opportunityId);
        $opportunity->update(['stage_id' => $request->stage_id]);

        return response()->json($opportunity);
    }
}
