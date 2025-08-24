<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            Session::put('user', $user);
            return redirect()->intended('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function showForgetPassword()
    {
        return view('auth.forgetpassword');
    }

    public function forgetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users,email'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Invalid crdentials');
        }
        return redirect()->route('resetpassword', [
            'email' => $user->email
        ]);
    }

    public function showResetPassword($email)
    {

        return view('auth.password-reset', [
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required'
        ]);

        $user =  User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully, Login with the new credentials');
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out.');
    }

    public function users()
    {

        $users = User::with(['company', 'branch'])->latest()->paginate(10);
        $companies = Company::all();
        $branches = Branch::all();
        return view('auth.index', [
            'users' => $users,
            'companies' => $companies,
            'branches' => $branches,

        ]);
    }


    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,staff',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'required|min:6',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User added successfully',
            'user' => $user
        ]);
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all();
        $branches = Branch::where('company_id', $user->company_id)->get(); // Filtered

        return view('auth.edit', [
            'user' => $user,
            'companies' => $companies,
            'branches' => $branches
        ]);
    }


    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,manager,staff',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->company_id = $request->company_id;
        $user->branch_id = $request->branch_id;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'User Updated successfully');
    }




    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User Deleted successfully');
    }


    public function getBranchesByCompany(Company $company)
    {
        return response()->json(
            $company->branches()->select('id', 'name')->get()
        );
    }
}
