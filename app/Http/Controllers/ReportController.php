<?php

namespace App\Http\Controllers;

use App\Models\OrganizationAssets;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asset_count(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $assets = OrganizationAssets::with('assetregister.organization')->whereDate('created_at', '>=', $request->from_date)->whereDate('created_at', '<=', $request->to_date)->get()
        ->filter(function ($asset) {
            return $asset->assetregister && $asset->assetregister->organization;
        });

        // Group by organization_id
        $grouped = $assets->groupBy(function ($asset) {
            return $asset->assetregister->organization->id;
        });

        $result = [];
        $totalCount = 0;

        foreach ($grouped as $orgId => $items) {
            $orgName = $items->first()->assetRegister->organization->name;
            $count = $items->count();
            $totalCount += $count;

            $result[] = [
                'organization_name' => $orgName,
                'asset_count' => $count,
            ];
        }

        return response()->json([
            'data' => $result,
            'total' => $totalCount
        ]);
    }
}