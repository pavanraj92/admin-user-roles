<?php

namespace admin\user_roles\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\user_roles\Requests\UserRoleCreateRequest;
use admin\user_roles\Requests\UserRoleUpdateRequest;
use admin\users\Models\UserRole;

class UserRoleManagerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user_roles = UserRole::
                filter($request->query('role'))
                ->filterByStatus($request->query('status'))
                ->latest()
                ->paginate(UserRole::getPerPageLimit())
                ->withQueryString();
         
            return view('user_role::admin.index', compact('user_roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_roles: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('user_role::admin.createOrEdit');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_roles: ' . $e->getMessage());
        }
    }

    public function store(UserRoleCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            UserRole::create($requestData);
            return redirect()->route('admin.user_roles.index')->with('success', 'User Role created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_roles: ' . $e->getMessage());
        }
    }

    /**
     * show user_role details
     */
    public function show(UserRole $user_role)
    {
        try {
            return view('user_role::admin.show', compact('user_role'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_roles: ' . $e->getMessage());
        }
    }

    public function edit(UserRole $user_role)
    {
        try {
            return view('user_role::admin.createOrEdit', compact('user_role'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_role for editing: ' . $e->getMessage());
        }
    }

    public function update(UserRoleUpdateRequest $request, UserRole $user_role)
    {
        try {
            $requestData = $request->validated();

            $user_role->update($requestData);
            return redirect()->route('admin.user_roles.index')->with('success', 'User Role updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load user_role for editing: ' . $e->getMessage());
        }
    }

    public function destroy(UserRole $user_role)
    {
        try {
            $user_role->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user_role = UserRole::findOrFail($request->id);
            $user_role->status = $request->status;
            $user_role->save();

            // create status html dynamically        
            $dataStatus = $user_role->status == '1' ? '0' : '1';
            $label = $user_role->status == '1' ? 'Active' : 'InActive';
            $btnClass = $user_role->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $user_role->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.user_roles.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $user_role->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to '.$label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
