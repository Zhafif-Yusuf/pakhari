@extends('layouts.app')
@section('content')
<h2>Profil Saya</h2>
<form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    <table style="border: none; text-align: left;">
        <tr>
            <td><label for="username">Username</label></td>
            <td><input type="text" name="username" 
            value="{{ $user->username }}"></td>
        </tr>
        <tr>
            <td><label for="namaLengkap">Nama Lengkap</label></td>
            <td><input type="text" name="namaLengkap" 
            value="{{ $user->namaLengkap }}"></td>
        </tr>
        <tr>
            <td><label for="email">Email</label></td>
            <td><input type="email" name="email" 
            value="{{ $user->email }}"></td>
        </tr>
        <tr>
            <td><label for="password">Password</label></td>
            <td><input type="password" name="password"></td>
        </tr>
        <tr>
            <td><label for="password_confirmation">Konfirmasi Password</label></td>
            <td><input type="password" name="password_confirmation"></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit">Simpan</button></td>
        </tr>
    </table>
</form>
<!-- Logout Form -->
<table>
    <tr>
        <td>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </td>
    </tr>
</table>
@endsection
