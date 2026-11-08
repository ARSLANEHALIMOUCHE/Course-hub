<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function users(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($subQuery) use ($request): void {
                $term = '%'.$request->string('search').'%';
                $subQuery->where('name', 'like', $term)->orWhere('email', 'like', $term);
            }))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser(): View
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $this->userData($request);
        $data['password'] = bcrypt($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Le compte a été créé.');
    }

    public function editUser(User $user): View
    {
        return view('admin.users.form', compact('user'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403, 'Vous ne pouvez pas modifier votre propre compte ici.');

        $data = $this->userData($request, $user);
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Le compte a été mis à jour.');
    }

    public function toggleUser(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403, 'Vous ne pouvez pas désactiver votre propre compte.');
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Le statut du compte a été mis à jour.');
    }

    public function subjects(): View
    {
        return view('admin.subjects.index', ['subjects' => Subject::withCount('courses')->orderBy('name')->get()]);
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        Subject::create($request->validate(['name' => ['required', 'string', 'max:100', 'unique:subjects,name']]));

        return back()->with('success', 'La matière a été créée.');
    }

    public function updateSubject(Request $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('subjects', 'name')->ignore($subject)],
        ]));

        return back()->with('success', 'La matière a été mise à jour.');
    }

    public function deleteSubject(Subject $subject): RedirectResponse
    {
        if ($subject->courses()->exists()) {
            return back()->withErrors(['subject' => 'Cette matière contient des cours et ne peut pas être supprimée.']);
        }
        $subject->delete();

        return back()->with('success', 'La matière a été supprimée.');
    }

    private function userData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(['admin', 'teacher', 'student'])],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
    }
}
