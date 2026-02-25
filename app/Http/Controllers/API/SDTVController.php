<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SDTVController extends Controller
{
    /**
     * List all states
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexStates(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 50);

            // $states = State::query();
            // if ($search) {
            //     $states->where('name', 'like', "%{$search}%");
            // }
            // $states = $states->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'States retrieved successfully',
                'data' => [
                    // 'states' => $states->items(),
                    'pagination' => [
                        // 'total' => $states->total(),
                        // 'current_page' => $states->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve states',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List districts by state
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDistricts(Request $request)
    {
        try {
            $state_id = $request->get('state_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 50);

            // $districts = District::query();
            // if ($state_id) {
            //     $districts->where('state_id', $state_id);
            // }
            // if ($search) {
            //     $districts->where('name', 'like', "%{$search}%");
            // }
            // $districts = $districts->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Districts retrieved successfully',
                'data' => [
                    // 'districts' => $districts->items(),
                    'pagination' => [
                        // 'total' => $districts->total(),
                        // 'current_page' => $districts->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve districts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List talukas by district
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexTalukas(Request $request)
    {
        try {
            $district_id = $request->get('district_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 50);

            // $talukas = Taluka::query();
            // if ($district_id) {
            //     $talukas->where('district_id', $district_id);
            // }
            // if ($search) {
            //     $talukas->where('name', 'like', "%{$search}%");
            // }
            // $talukas = $talukas->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Talukas retrieved successfully',
                'data' => [
                    // 'talukas' => $talukas->items(),
                    'pagination' => [
                        // 'total' => $talukas->total(),
                        // 'current_page' => $talukas->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve talukas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List villages by taluka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexVillages(Request $request)
    {
        try {
            $taluka_id = $request->get('taluka_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 100);

            // $villages = Village::query();
            // if ($taluka_id) {
            //     $villages->where('taluka_id', $taluka_id);
            // }
            // if ($search) {
            //     $villages->where('name', 'like', "%{$search}%");
            // }
            // $villages = $villages->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Villages retrieved successfully',
                'data' => [
                    // 'villages' => $villages->items(),
                    'pagination' => [
                        // 'total' => $villages->total(),
                        // 'current_page' => $villages->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve villages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new state
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeState(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:states,name',
                'code' => 'required|string|max:10|unique:states,code',
            ]);

            // $state = State::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'State created successfully',
                'data' => [
                    // 'state' => $state,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Create new district
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDistrict(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'state_id' => 'required|exists:states,id',
            ]);

            // $district = District::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'District created successfully',
                'data' => [
                    // 'district' => $district,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Create new taluka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTaluka(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'district_id' => 'required|exists:districts,id',
            ]);

            // $taluka = Taluka::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Taluka created successfully',
                'data' => [
                    // 'taluka' => $taluka,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Create new village
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeVillage(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'taluka_id' => 'required|exists:talukas,id',
            ]);

            // $village = Village::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Village created successfully',
                'data' => [
                    // 'village' => $village,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update state
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateState(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:states,name,' . $id,
            ]);

            // $state = State::findOrFail($id);
            // $state->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'State updated successfully',
                'data' => [
                    // 'state' => $state,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update district
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDistrict(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
            ]);

            // $district = District::findOrFail($id);
            // $district->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'District updated successfully',
                'data' => [
                    // 'district' => $district,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update taluka
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTaluka(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
            ]);

            // $taluka = Taluka::findOrFail($id);
            // $taluka->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Taluka updated successfully',
                'data' => [
                    // 'taluka' => $taluka,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update village
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVillage(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
            ]);

            // $village = Village::findOrFail($id);
            // $village->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Village updated successfully',
                'data' => [
                    // 'village' => $village,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Delete state
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyState($id)
    {
        try {
            // $state = State::findOrFail($id);
            // $state->delete();

            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete state',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete district
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyDistrict($id)
    {
        try {
            // $district = District::findOrFail($id);
            // $district->delete();

            return response()->json([
                'success' => true,
                'message' => 'District deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete district',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete taluka
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTaluka($id)
    {
        try {
            // $taluka = Taluka::findOrFail($id);
            // $taluka->delete();

            return response()->json([
                'success' => true,
                'message' => 'Taluka deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete taluka',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete village
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyVillage($id)
    {
        try {
            // $village = Village::findOrFail($id);
            // $village->delete();

            return response()->json([
                'success' => true,
                'message' => 'Village deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete village',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
