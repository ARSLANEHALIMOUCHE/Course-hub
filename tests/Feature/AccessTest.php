<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_creates_only_student_accounts(): void
    {
        $this->post('/register', [
            'name' => 'Demo Student',
            'email' => 'student@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'student@example.test', 'role' => 'student']);
    }

    public function test_inactive_accounts_cannot_log_in(): void
    {
        User::create(['name' => 'Inactive', 'email' => 'inactive@example.test', 'password' => 'password123', 'role' => 'student', 'is_active' => false]);

        $this->post('/login', ['email' => 'inactive@example.test', 'password' => 'password123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_students_cannot_open_admin_or_teacher_screens(): void
    {
        $student = User::create(['name' => 'Student', 'email' => 'student@example.test', 'password' => 'password123', 'role' => 'student', 'is_active' => true]);
        $this->actingAs($student)->get('/admin/users')->assertForbidden();
        $this->actingAs($student)->get('/teacher/courses')->assertForbidden();
    }

    public function test_students_only_see_published_courses(): void
    {
        $teacher = User::create(['name' => 'Teacher', 'email' => 'teacher@example.test', 'password' => 'password123', 'role' => 'teacher', 'is_active' => true]);
        $subject = Subject::create(['name' => 'Sciences']);
        Course::create(['teacher_id' => $teacher->id, 'subject_id' => $subject->id, 'title' => 'Cours public', 'description' => 'Description', 'content' => 'Leçon publique', 'status' => 'published', 'published_at' => now()]);
        Course::create(['teacher_id' => $teacher->id, 'subject_id' => $subject->id, 'title' => 'Cours brouillon', 'description' => 'Description secrète', 'content' => 'Leçon privée', 'status' => 'draft']);
        $student = User::create(['name' => 'Student', 'email' => 'student@example.test', 'password' => 'password123', 'role' => 'student', 'is_active' => true]);

        $this->actingAs($student)->get('/student/courses')
            ->assertOk()
            ->assertSee('Cours public')
            ->assertDontSee('Cours brouillon');
    }

    public function test_teachers_cannot_edit_another_teachers_course(): void
    {
        $owner = User::create(['name' => 'Owner', 'email' => 'owner@example.test', 'password' => 'password123', 'role' => 'teacher', 'is_active' => true]);
        $otherTeacher = User::create(['name' => 'Other', 'email' => 'other@example.test', 'password' => 'password123', 'role' => 'teacher', 'is_active' => true]);
        $subject = Subject::create(['name' => 'Mathématiques']);
        $course = Course::create(['teacher_id' => $owner->id, 'subject_id' => $subject->id, 'title' => 'Cours privé', 'description' => 'Description', 'content' => 'Contenu', 'status' => 'draft']);

        $this->actingAs($otherTeacher)->put('/teacher/courses/'.$course->id, [
            'subject_id' => $subject->id,
            'title' => 'Cours modifié',
            'description' => 'Description modifiée',
            'content' => 'Contenu modifié',
            'status' => 'published',
        ])->assertForbidden();

        $this->assertDatabaseHas('courses', ['id' => $course->id, 'title' => 'Cours privé']);
    }
}
