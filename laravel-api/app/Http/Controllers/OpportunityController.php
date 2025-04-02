<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    // Lấy danh sách opportunity với filter
    public function index(Request $request)
    {
        $query = Opportunity::query();

        // 📝 Lọc theo ngày tạo
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // 📝 Lọc theo người tạo
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // 📝 Lọc theo manager
        if ($request->has('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        // 📝 Tìm kiếm theo text
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // 📝 Lọc theo tag
        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        // 📌 Lấy danh sách Opportunity có phân trang
        return response()->json($query->with('tags')->paginate(10));
    }

    // Tạo mới opportunity
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'created_by' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $opportunity = Opportunity::create($request->all());

        return response()->json($opportunity, 201);
    }

    // Xem chi tiết 1 opportunity
    public function show($id)
    {
        return response()->json(Opportunity::findOrFail($id));
    }

    // Cập nhật opportunity
    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);
        $opportunity->update($request->all());

        return response()->json($opportunity);
    }

    // Xoá 1 opportunity
    public function destroy($id)
    {
        Opportunity::destroy($id);
        return response()->json(['message' => 'Đã xoá']);
    }

    // Xoá nhiều Opportunity
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:opportunities,id'
        ]);

        Opportunity::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Đã xoá các opportunity']);
    }

    // Cập nhật nhiều Opportunity
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'data' => 'required|array'
        ]);

        foreach ($request->data as $item) {
            $op = Opportunity::find($item['id']);
            if ($op) {
                $op->update($item);
            }
        }

        return response()->json(['message' => 'Đã cập nhật các opportunity']);
    }

    // Liên kết opportunity với tags
    public function attachTag(Request $request, $opportunityId)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        $opportunity = Opportunity::findOrFail($opportunityId);
        $opportunity->tags()->attach($request->tag_id);

        return response()->json($opportunity->load('tags'));
    }

    public function detachTag(Request $request, $opportunityId)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        $opportunity = Opportunity::findOrFail($opportunityId);
        $opportunity->tags()->detach($request->tag_id);

        return response()->json($opportunity->load('tags'));
    }

    public function filterByTag(Request $request)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        $opportunities = Opportunity::whereHas('tags', function ($query) use ($request) {
            $query->where('tag_id', $request->tag_id);
        })->with('tags')->get();

        return response()->json($opportunities);
    }

    public function getRelatedOpportunities($opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);
        $tagIds = $opportunity->tags->pluck('id');

        $relatedOpportunities = Opportunity::where('id', '!=', $opportunityId)
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tag_id', $tagIds);
            })
            ->with('tags')
            ->get();

        return response()->json($relatedOpportunities);
    }
}
