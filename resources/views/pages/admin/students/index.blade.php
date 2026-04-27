@extends('layouts.admin', [
'pageTitle' => 'Students',
'pageSubtitle' => 'Students',
])

@section('content')
@php
$students = [
[
'id' => 'STD-001',
'name' => 'James Dougherty',
'email' => 'james.d@example.com',
'whatsapp' => '+44 7911 123456',
'registeredDate' => 'Oct 12, 2023',
'coursesCount' => 3,
'status' => 'active',
'statusLabel' => 'Active',
'memberType' => 'Premium Member',
'initials' => 'JD',
'avatarColor' => 'bg-blue-100 text-blue-700',
'image' => '',
],
[
'id' => 'STD-002',
'name' => 'Sarah Jenkins',
'email' => 's.jenkins@icloud.com',
'whatsapp' => '+44 7911 889999',
'registeredDate' => 'Sep 28, 2023',
'coursesCount' => 1,
'status' => 'active',
'statusLabel' => 'Active',
'memberType' => 'Standard Member',
'initials' => 'SJ',
'avatarColor' => 'bg-orange-100 text-orange-700',
'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop',
],
[
'id' => 'STD-003',
'name' => 'Michael Kovac',
'email' => 'mkovac@edu.it',
'whatsapp' => '+39 333 444 555',
'registeredDate' => 'Aug 05, 2023',
'coursesCount' => 0,
'status' => 'inactive',
'statusLabel' => 'Inactive',
'memberType' => 'Standard Member',
'initials' => 'MK',
'avatarColor' => 'bg-slate-100 text-slate-600',
'image' => '',
],
[
'id' => 'STD-004',
'name' => "Liam O'Connell",
'email' => 'liam.oc@gmail.com',
'whatsapp' => '+353 87 123 4567',
'registeredDate' => 'Jul 19, 2023',
'coursesCount' => 5,
'status' => 'active',
'statusLabel' => 'Active',
'memberType' => 'Premium Member',
'initials' => 'LO',
'avatarColor' => 'bg-emerald-100 text-emerald-700',
'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=200&auto=format&fit=crop',
],
[
'id' => 'STD-005',
'name' => 'Elena Castros',
'email' => 'ecastros@biz.mx',
'whatsapp' => '+52 55 1234 5678',
'registeredDate' => 'Jun 30, 2023',
'coursesCount' => 2,
'status' => 'active',
'statusLabel' => 'Active',
'memberType' => 'Standard Member',
'initials' => 'EC',
'avatarColor' => 'bg-indigo-100 text-indigo-700',
'image' => '',
],
];
@endphp

<section
    x-data="{
        search: '',
        statusFilter: 'all',
        addStudentModalOpen: false,
        studentDetailModalOpen: false,
        activeDetailTab: 'profile',
        selectedStudent: null,
        students: @js($students),

        openStudent(student) {
            this.selectedStudent = student;
            this.activeDetailTab = 'profile';
            this.studentDetailModalOpen = true;
        },

        matches(student) {
            const keyword = this.search.toLowerCase();

            const searchMatch =
                student.name.toLowerCase().includes(keyword) ||
                student.email.toLowerCase().includes(keyword) ||
                student.whatsapp.toLowerCase().includes(keyword);

            const statusMatch =
                this.statusFilter === 'all' ||
                student.status === this.statusFilter;

            return searchMatch && statusMatch;
        }
    }"
    class="mx-auto max-w-7xl space-y-6">
    @include('partials.admin.students.header')
    @include('partials.admin.students.filters')
    @include('partials.admin.students.table')
    @include('partials.admin.students.add-student-modal')
</section>
@endsection